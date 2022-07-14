<?php

namespace App\Controller;

use App\Repository\PartnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PartnerRepository $partnerRepository): Response
    {
        $activePartners = $partnerRepository->findBy(['is_active' => true]);
        $notActivePartners = $partnerRepository->findBy(['is_active' => false]);
        return $this->render('home/index.html.twig', [
            'partners' => $partnerRepository->findAll(),
            'activePartners' => $activePartners,
            'notActivePartners' => $notActivePartners
        ]);
    }
}
