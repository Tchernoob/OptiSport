<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\Mods;
use App\Entity\User;
use App\Form\PartnerType;
use App\Form\StructureType;
use App\Form\UserType;
use App\Repository\PartnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/partner')]
class PartnerController extends AbstractController
{
    #[Route('/', name: 'app_partner')]
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

    #[Route('/new', name: 'new_partner')]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response
    {
        $partner = new Partner();

        $form = $this->createForm(PartnerType::class, $partner);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())

        {
            $file = $form['logo']->getData();
            $extension = $file->guessExtension();
            if(!$extension)
            {
                $extension = 'bin';
            }

            $newName = $slugger->slug($file->getClientOriginalName()).'-'.uniqid().'.'.$extension;
            $file->move($this->getParameter('logo_partner_directory'), $newName);

            $partner
                ->setLogo($newName)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            //pour l'envoi dans la bdd
            $manager->persist($partner);

//            $form
//                ->getData()->getUser()->getPassword()->setData('1234')
//                ->getData()->getUser()->getCreatedAt()->setData(new \DateTime())
//                ->getData()->getUser()->getPartner()->setData($partner);

            $manager->flush();


            return $this->redirectToRoute('app_partner_show', ['id' => $partner->getId()]);
        }

        return $this->renderForm('partner/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_partner_show', methods: ['GET'])]
    public function show(Partner $partner): Response
    {
        return $this->render('partner/show.html.twig', [
            'partner' => $partner,
            'structures' => $partner->getStructures(),
            'mods' => $partner->getMods(),
        ]);
    }

    #[Route('/newStructure/{id}', name: 'new_structure')]
    public function newStructure(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, Partner $partner): Response
    {

        $structure = new Structure();

        $form = $this->createForm(StructureType::class, $structure);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $file = $form['logo']->getData();
            $extension = $file->guessExtension();
            if(!$extension)
            {
                $extension = 'bin';
            }

            $newName = $slugger->slug($file->getClientOriginalName()).'-'.uniqid().'.'.$extension;
            $file->move($this->getParameter('logo_structure_directory'), $newName);


            $structure
                ->setLogo($newName)
                ->setPartner($partner)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            //pour l'envoi dans la bdd
            $manager->persist($structure);
            $manager->flush();

            return $this->redirectToRoute('app_partner_show', ['id' => $partner->getId()]);
        }

        return $this->renderForm('structure/new.html.twig', [
            'form' => $form,
        ]);
    }
}
