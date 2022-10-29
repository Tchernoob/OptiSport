<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Form\StructureEditRightType;
use App\Form\StructureEditType;
use App\Repository\StructureRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ModsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/structure')]
class StructureController extends AbstractController
{
    #[Route('/', name: 'app_structure')]
    public function index(StructureRepository $structureRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }

        $structures = $structureRepository->findAll();
        $activeStructures = $structureRepository->findBy(['is_active' => true]);
        $notActiveStructures = $structureRepository->findBy(['is_active' => false]);
        return $this->render('structure/index.html.twig', [
            'structures' => $structures,
            'activeStructures' => $activeStructures,
            'notActiveStructures' => $notActiveStructures
        ]);
    }


    #[Route('/{id}', name: 'app_structure_show', methods: ['GET'])]
    public function show(Structure $structure,  ModsRepository $modRepo, UserInterface $user): Response
    {
        $partnerId = $structure->getPartner()->getId();
        $partnerUserId = $structure->getPartner()->getUser()->getId();
        $userId = $user->getId(); 
        $userStructureId = $structure->getUser()->getId();

        if ($userId !== $partnerUserId && $userId !== $userStructureId && !$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à voir le détail de cette structure');
        }
            
        $structureMods = $structure->getMods();
        return $this->render('structure/show.html.twig', [
            'structure' => $structure,
            'mods' => $modRepo->findBy(['is_active' => true]), 
            'structureMods' => $structureMods
        ]);
    }

    #[Route('/activate/{id}', name: 'activate_structure', methods: ['GET'])]
    public function activateStructure(EntityManagerInterface $em, Structure $structure) : Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }

        if($structure->isIsActive())
        {
            $structure->setIsActive(false);
        }
        else 
        {
            $structure->setIsActive(true);
        }

        $em->persist($structure); 
        $em->flush(); 

        return new JsonResponse($structure->isIsActive());
    }

    #[Route('/{id}/edit', name: 'edit_structure')]
    public function edit(Request $request, Structure $structure, EntityManagerInterface $manager, SluggerInterface $slugger,  MailerInterface $mailer) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $structure->getUser(); 
        $user->setStructure($structure);
        $modules = $structure->getMods();
        
        $password = $user->getPassword();
 
        $form = $this->createForm(StructureEditType::class, $structure);
        
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
                ->setUpdatedAt(new \DateTime());
            
            $user
                ->setRoles(['ROLE_USER_STRUCTURE'])
                ->setPassword($password);

            //pour l'envoi dans la bdd
            $manager->persist($structure);
            $manager->persist($user);

            $manager->flush();

            $message = (new Email())
                ->from('test@optisport.com')
                ->to($formData->getUser()->getEmail())
                ->subject('Votre structure a été modifié')
                ->text('Un Administrateur vient de modifier votre Structure');
            $mailer->send($message);

            return $this->redirectToRoute('app_structure_show', [
                'id' => $structure->getId(),
            ]);
        }

        return $this->renderForm('structure/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit-module-right', name: 'edit__module_right_structure')]
    public function editRight(Request $request, Structure $structure, EntityManagerInterface $manager, SluggerInterface $slugger,  MailerInterface $mailer) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
      
        $user = $structure->getUser(); 
        $user->setStructure($structure); 
        $form = $this->createForm(StructureEditRightType::class, $structure);
        $password = $user->getPassword();
        $logo = $structure->getLogo();
        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $formData = $form->getData(); 

            $structure
                ->setLogo($logo)
                ->setUpdatedAt(new \DateTime());
            
            $user
                ->setRoles(['ROLE_USER_STRUCTURE'])
                ->setPassword($password);

            //pour l'envoi dans la bdd
            $manager->persist($structure);
            $manager->persist($user);

            $manager->flush();

            $message = (new Email())
                ->from('test@optisport.com')
                ->to($formData->getUser()->getEmail())
                ->subject('Les droit de votre structure ont été modifié')
                ->text('Un Administrateur vient de modifier les droits de votre Structure');
            $mailer->send($message);

            return $this->redirectToRoute('app_structure_show', [
                'id' => $structure->getId(),
            ]);
        }

        return $this->renderForm('structure/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_structure', methods: ['POST'])]
    public function deleteAdmin(Request $request, Structure $structure, EntityManagerInterface $entityManager) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager->remove($structure);
        $entityManager->flush();

        return $this->redirectToRoute('app_partner', [], Response::HTTP_SEE_OTHER);
    }
}
