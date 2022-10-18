<?php

namespace App\Controller;

use App\Form\CreatePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
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
}