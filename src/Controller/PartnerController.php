<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Repository\PartnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends AbstractController
{
    #[Route('/partner', name: 'app_partner')]
    public function index(PartnerRepository $partnerRepository): Response
    {
        $partners = $partnerRepository->findAll();
        $activePartners = $partnerRepository->findBy(['is_active' => true]);
        $notActivePartners = $partnerRepository->findBy(['is_active' => false]);


        return $this->render('partner/index.html.twig', [
            'partners' => $partners,
            'activePartners' => $activePartners,
            'notActivePartners' => $notActivePartners
        ]);
    }
}
