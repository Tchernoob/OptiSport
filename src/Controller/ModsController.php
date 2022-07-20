<?php

namespace App\Controller;

use App\Repository\ModsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModsController extends AbstractController
{
    #[Route('/mods', name: 'app_mods')]
    public function index(ModsRepository $modsRepository): Response
    {
        $modules = $modsRepository->findAll();

        return $this->render('mods/index.html.twig.', [
            'modules' => $modules,
        ]);
    }
}
