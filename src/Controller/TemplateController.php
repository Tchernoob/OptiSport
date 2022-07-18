<?php

namespace App\Controller;

use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{
    #[Route('/template', name: 'app_template')]
    public function index(TemplateRepository $templateRepository): Response
    {
        $templates = $templateRepository->findAll();
        return $this->render('template/index.html.twig', [
            'templates' => $templates,
        ]);
    }
}
