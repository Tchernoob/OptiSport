<?php

namespace App\Controller;

use App\Entity\Mods;
use App\Entity\User;
use App\Entity\Partner;
use App\Entity\Structure;
use App\Form\PartnerEditType;
use App\Form\PartnerType;
use App\Form\StructureType;
use App\Repository\ModsRepository;
use App\Repository\PartnerRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/partner')]
class PartnerController extends AbstractController
{
    #[Route('/', name: 'app_partner')]
    public function index(PartnerRepository $partnerRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }

        $partners = $partnerRepository->findAll();
        $activePartners = $partnerRepository->findBy(['is_active' => true]);
        $notActivePartners = $partnerRepository->findBy(['is_active' => false]);

        return $this->render('partner/index.html.twig', [
            'partners' => $partners,
            'activePartners' => $activePartners,
            'notActivePartners' => $notActivePartners
        ]);
    }

    #[Route('/new', name: 'new_partner')]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, MailerInterface $mailer) : Response
    {

        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }

        $partner = new Partner();
        $user = new User(); 
        $user->setPartner($partner); 
        $form = $this->createForm(PartnerType::class, $partner);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $formData = $form->getData(); 

            $file = $form['logo']->getData();
            $extension = $file->guessExtension();
            if(!$extension)
            {
                $extension = 'bin';
            }

            $newName = $slugger->slug($file->getClientOriginalName()).'-'.uniqid().'.'.$extension;
            $file->move($this->getParameter('logo_partner_directory'), $newName);

            $partner
                ->setLogo($newName)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());
            
            $user
            ->setRoles(['ROLE_MANAGER']);

            //pour l'envoi dans la bdd
            $manager->persist($partner);
            $manager->persist($user);

            $manager->flush();

            $message = (new Email())
                ->from('test@optisport.com')
                ->to($formData->getUser()->getEmail())
                ->subject('Bienvenue chez OptiSport !')
                ->text('http://127.0.0.1:8000/user/create-password/'.$formData->getUser()->getPassword());
            $mailer->send($message);

            return $this->redirectToRoute('app_partner_show', [
                'id' => $partner->getId(), 
            ]);
        }

        return $this->renderForm('partner/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partner_show', methods: ['GET'])]
    public function show(Partner $partner, ModsRepository $modRepo, UserInterface $user): Response
    {
        $userId = $user->getId();       
        $userpartnerId = $partner->getUser()->getId();
        if (!$this->isGranted('ROLE_ADMIN') && $userId != $userpartnerId)
        {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à voir le détail de ce partenaire');
        }

        return $this->render('partner/show.html.twig', [
            'partner' => $partner,
            'structures' => $partner->getStructures(),
            'template' => $partner->getTemplate(),
            'mods' => $modRepo->findBy(['is_active' => true]), 
        ]);
    }

    #[Route('/newStructure/{id}', name: 'new_structure')]
    public function newStructure(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, Partner $partner, MailerInterface $mailer): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }

        $structure = new Structure();
        $user = new User();
        $user->setStructure($structure);
        $form = $this->createForm(StructureType::class, $structure, [
            'partner_id' => $partner->getId()
        ]);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $formData = $form->getData(); 

            $file = $form['logo']->getData();
            $extension = $file->guessExtension();
            if(!$extension)
            {
                $extension = 'bin';
            }

            $newName = $slugger->slug($file->getClientOriginalName()).'-'.uniqid().'.'.$extension;
            $file->move($this->getParameter('logo_structure_directory'), $newName);

            $structure
                ->setLogo($newName)
                ->setPartner($partner)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());
            
            $user
                ->setRoles(['ROLE_USER_STRUCTURE']);

            //pour l'envoi dans la bdd
            $manager->persist($structure);
            $manager->persist($user);

            $mods = $partner->getMods(); 
            foreach($mods as $mod)
            {
                $structure->addMods($mod); 
            }

            $template = $partner->getTemplate(); 
            $structure->setTemplate($template); 

            $manager->flush();

            $message = (new Email())
                ->from('test@optisport.com')
                ->to($formData->getUser()->getEmail())
                ->subject('Bienvenue chez Optisport !')
                ->text('http://127.0.0.1:8000/user/create-password/'.$formData->getUser()->getPassword());
            $mailer->send($message);

            return $this->redirectToRoute('app_structure_show', ['id' => $structure->getId()]);
        }

        return $this->renderForm('structure/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/mod/{mod}/activate/{id}', name: 'activate_mod', methods: ['GET'])]
    public function activateModule(EntityManagerInterface $em, Partner $partner, Mods $mod) : JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }

        $exists = false; 
      
        //si le module focus est présent dans la liste des modules du partner on flag true
        foreach($partner->getMods() as $module)
        {
            if($module === $mod)
            {
                $exists = true; 
            }
        }
        //si true on le supprime de la liste sinon on l'ajoute 
        if($exists) 
        {
            $partner->removeMods($mod);
            foreach($partner->getStructures() as $struct){
                $struct->removeMods($mod);
            }
        }
        else 
        {
            $partner->addMods($mod);
            foreach($partner->getStructures() as $struct){
                $struct->addMods($mod);
            }
        }
      
        $em->persist($partner); 
        $em->flush(); 

        //on créé un tableau avec un id et nom de chaque module dans le partner après ajout ou suppression du dessus 
        //on renvoie ce tableau au client js comme réponse lors du clic sur un bouton
        $partnerMods = []; 
        foreach($partner->getMods() as $partnerMod) 
        {
            $partnerMods[] = ['id'=>$partnerMod->getId(), 'name'=>$partnerMod->getName()]; ; 
        }


        return new JsonResponse($partnerMods); 
    }

    #[Route('/activate/{id}', name: 'activate_partner', methods: ['GET'])]
    public function activatePartner(EntityManagerInterface $em, Partner $partner) : Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }

        $structures = $partner->getStructures();

        if($partner->isIsActive())
        {
            $partner->setIsActive(false);
            foreach($structures as $structure)
            {
                $structure->setIsActive(false);
            }
        }
        else 
        {
            $partner->setIsActive(true);
            foreach($structures as $structure)
            {
                $structure->setIsActive(true);
            }
        }

        $em->persist($partner); 
        $em->flush(); 

        return new JsonResponse($partner->isIsActive());

    }

    #[Route('/filter/{val}', name: 'filter_partner', methods: ['GET'])]
    public function filterPartner(PartnerRepository $pr, $val) : JsonResponse
    {
     
        $partnerFiltered = $pr->findByCriteria($val);

        $result = []; 
       
        foreach($partnerFiltered as $partner)
        {
            $date = $partner->getcreatedAt()->format('d-m-Y');
            
            $template = "";
            if ($partner->getTemplate())
            {
                $template = $partner->getTemplate()->getName();
            } 
            else 
            {
                $template = "Aucun";
            }

            $data =
            [
                'id' => $partner->getId(), 
                'name' => $partner->getName(),
                'status' => $partner->isIsActive(), 
                'url' => $partner->getUrl(),
                'createdAt' => $date,
                'template' => $template,
            ];

            $result[] = $data; 
        }
        return new JsonResponse($result);
    }

    #[Route('/filterStatus/{val}', name: 'filter_status_partner', methods: ['GET'])]
    public function filterStatusPartner(PartnerRepository $pr, $val) : JsonResponse
    {
        
        $partnerStatusFiltered = $pr->findByStatus($val);

        $result = []; 

        
       
        foreach($partnerStatusFiltered as $partner)
        {
            $date = $partner->getcreatedAt()->format('d-m-Y');

            $template = "";
            if ($partner->getTemplate())
            {
                $template = $partner->getTemplate()->getName();
            } 
            else 
            {
                $template = "Aucun";
            }

            $data =
            [
                'id' => $partner->getId(), 
                'name' => $partner->getName(),
                'status' => $partner->isIsActive(), 
                'url' => $partner->getUrl(),
                'createdAt' => $date,
                'template' => $template,
            ];

            $result[] = $data; 
        }
        return new JsonResponse($result);
    }

    #[Route('{id}/edit', name: 'edit_partner')]
    public function edit(Request $request, Partner $partner, EntityManagerInterface $manager, SluggerInterface $slugger,  MailerInterface $mailer) : Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }
      
        $user = $partner->getUser(); 
        $user->setPartner($partner); 
        $form = $this->createForm(PartnerEditType::class, $partner);
        $password = $user->getPassword();
        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $formData = $form->getData(); 

            $file = $form['logo']->getData();
            $extension = $file->guessExtension();
            if(!$extension)
            {
                $extension = 'bin';
            }

            $newName = $slugger->slug($file->getClientOriginalName()).'-'.uniqid().'.'.$extension;
            $file->move($this->getParameter('logo_partner_directory'), $newName);

            $partner
                ->setLogo($newName)
                ->setUpdatedAt(new \DateTime());
            
            $user
                ->setRoles(['ROLE_MANAGER'])
                ->setPassword($password);

            //pour l'envoi dans la bdd
            $manager->persist($partner);
            $manager->persist($user);

            $manager->flush();

            $message = (new Email())
                ->from('test@optisport.com')
                ->to($formData->getUser()->getEmail())
                ->subject('Votre partenaire a été modifié')
                ->text('Un Administrateur vient de modifier votre partenaire');
            $mailer->send($message);

            return $this->redirectToRoute('app_partner_show', [
                'id' => $partner->getId(), 
            ]);
        }

        return $this->renderForm('partner/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_partner', methods: ['POST'])]
    public function deleteAdmin(Partner $partner, EntityManagerInterface $entityManager) : Response
    {        
        $entityManager->remove($partner);
        $entityManager->flush();

        return $this->redirectToRoute('app_partner', [], Response::HTTP_SEE_OTHER);
    }
}