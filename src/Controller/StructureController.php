<?php

namespace App\Controller;

use App\Form\StructureType;
use App\Repository\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
}
