<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Repository\StructureRepository;
use App\Repository\ModsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function show(Structure $structure, ModsRepository $modRepo): Response
    {
        return $this->render('structure/show.html.twig', [
            'structure' => $structure,
            'mods' => $modRepo->findBy(['is_active' => true]), 
        ]);
    }
}
