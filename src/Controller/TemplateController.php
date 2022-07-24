<?php

namespace App\Controller;

use App\Entity\Template;
use App\Form\TemplateType;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/template')]
class TemplateController extends AbstractController
{
    #[Route('/', name: 'app_template')]
    public function index(TemplateRepository $templateRepository): Response
    {
        $templates = $templateRepository->findAll();
        return $this->render('template/index.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/new', name: 'new_template')]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response
    {
        $template = new Template();

        $form = $this->createForm(TemplateType::class, $template);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $template
                ->setCreatedAt(new \DateTime());

            $manager->persist($template);
            $manager->flush();

            return $this->redirectToRoute('/');
        }

        return $this->renderForm('template/new.html.twig', [
            'form' => $form,
        ]);

    }
}
