<?php

namespace App\Controller;

use App\Form\CreatePasswordType;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            throw $this->createAccessDeniedException('Seulement les administrateurs OptiSport peuvent accéder à cette partie de l\'application');
        }

        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/user/create-password/{token}', name: 'app_user_create_pwd')]
    public function createPwd(EntityManagerInterface $em, UserRepository $userRepo, UserPasswordHasherInterface $userPasswordHasher, Request $request, $token): Response
    {
        $exists = false; 

        foreach($userRepo->findAll() as $user)
        {
            if($user->getPassword() === $token)
            {
                $exists = true; 
                $user;
            } 
        }
        
        if($exists)
        {
            $form = $this->createForm(CreatePasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                // Encode(hash) the plain password, and set it.
                $encodedPassword = $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                );

                $user->setPassword($encodedPassword);
                $em->flush();
                return $this->redirectToRoute('app_login'); 
            }

        return $this->render('user/create-password.html.twig', [
            'createPwdForm' => $form->createView(),
        ]);

        }

        return $this->redirectToRoute('app_login'); 

    }

    #[Route('/new', name: 'new_admin')]
    public function new(Request $request, EntityManagerInterface $manager, MailerInterface $mailer) : Response
    {
        $user = new User(); 
        $form = $this->createForm(UserType::class, $user);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $formData = $form->getData(); 

            $user
            ->setRoles(['ROLE_ADMIN']);

            //pour l'envoi dans la bdd
            $manager->persist($user);
            $manager->flush();

            $message = (new Email())
                ->from('test@optisport.com')
                ->to($user->getEmail())
                ->subject('Bienvenue chew OptiSport !')
                ->text('http://127.0.0.1:8000/user/create-password/'.$user->getPassword());
            $mailer->send($message);

            return $this->redirectToRoute('app_user');
        }

        return $this->renderForm('user/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit_user', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $userConnected = $this->getUser(); 
        if(($user->getId() !== $this->getUser()) &&  !in_array('ROLE_ADMIN', $userConnected->getRoles())) {
            return $this->redirectToRoute('app_home'); 
        }           
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name: 'delete_admin', methods: ['POST'])]
    public function deleteAdmin(Request $request, User $user, EntityManagerInterface $entityManager, MailerInterface $mailer) : Response
    {        
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }
}