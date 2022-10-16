<?php

namespace App\Controller;

use App\Repository\PartnerRepository;
use App\Repository\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(PartnerRepository $partnerRepository, StructureRepository $structureRepository): Response
    {
        $structuresNumber = count($structureRepository->findAll());
        $activeStructuresNumber = count($structureRepository->findBy(['is_active' => true]));
        $activeStructures = count($structureRepository->findBy(['is_active' => true]));
        $noActiveStructures = count($structureRepository->findBy(['is_active' => false]));
        $partnersNumber = count($partnerRepository->findAll());
        $activePartnersNumber = count($partnerRepository->findBy(['is_active' => true]));
        $activePartners = $partnerRepository->findBy(['is_active' => true]);
        $notActivePartners = $partnerRepository->findBy(['is_active' => false]);
        return $this->render('home/index.html.twig', [
            'partners' => $partnerRepository->findAll(),
            'structures' => $structureRepository->findAll(),
            'activePartners' => $activePartners,
            'notActivePartners' => $notActivePartners,
            'activeStructures' => $activeStructures,
            'noActiveStructures' => $noActiveStructures,
            'partnersNumber' => $partnersNumber,
            'activePartnersNumber' => $activePartnersNumber,
            'structuresNumber' => $structuresNumber,
            'activeStructuresNumber' => $activeStructuresNumber
        ]);
    }
}
