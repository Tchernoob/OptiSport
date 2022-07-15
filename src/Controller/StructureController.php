<?php

namespace App\Controller;

use App\Repository\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController
{
    #[Route('/structure', name: 'app_structure')]
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
}
