<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Form\StructureEditType;
use App\Repository\StructureRepository;
use App\Repository\ModsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/structure')]
class StructureController extends AbstractController
{
    #[Route('/', name: 'app_structure')]
    public function index(StructureRepository $structureRepository): Response
    {
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
    public function show(Structure $structure,  ModsRepository $modRepo): Response
    {
        $structureMods = $structure->getMods();
        return $this->render('structure/show.html.twig', [
            'structure' => $structure,
            'mods' => $modRepo->findBy(['is_active' => true]), 
            'structureMods' => $structureMods
        ]);
    }

    #[Route('{id}/edit', name: 'edit_structure')]
    public function edit(Request $request, Structure $structure, EntityManagerInterface $manager, SluggerInterface $slugger,  MailerInterface $mailer) : Response
    {
      
        $user = $structure->getUser(); 
        $user->setStructure($structure); 
        $form = $this->createForm(StructureEditType::class, $structure);
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

            return $this->redirectToRoute('app_structure');
        }

        return $this->renderForm('structure/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_structure', methods: ['POST'])]
    public function deleteAdmin(Request $request, Structure $structure, EntityManagerInterface $entityManager) : Response
    {        
        $entityManager->remove($structure);
        $entityManager->flush();

        return $this->redirectToRoute('app_partner', [], Response::HTTP_SEE_OTHER);
    }


}
