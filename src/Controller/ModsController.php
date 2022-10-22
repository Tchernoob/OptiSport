<?php

namespace App\Controller;

use App\Entity\Mods;
use App\Entity\Template;
use App\Form\ModsType;
use App\Repository\ModsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/mods')]
class ModsController extends AbstractController
{
    #[Route('/', name: 'app_mods')]
    public function index(ModsRepository $modsRepository): Response
    {
        $modules = $modsRepository->findAll();

        return $this->render('mods/index.html.twig.', [
            'modules' => $modules,
        ]);
    }

    #[Route('/new', name: 'new_module')]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response
    {
        $module = new Mods();

        $form = $this->createForm(ModsType::class, $module);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $module
                ->setCreatedAt(new \DateTime());

            $manager->persist($module);
            $manager->flush();

            return $this->redirectToRoute('app_mods');
        }

        return $this->renderForm('mods/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_module', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mods $module, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(ModsType::class, $module);
        $form->handleRequest(($request));
        
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($module);
            $em->flush();

            return $this->redirectToRoute('app_mods');
        }

        return $this->renderForm('mods/edit.html.twig', [
            'form' => $form,
        ]);
    }    
}
