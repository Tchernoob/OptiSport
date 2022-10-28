<?php

namespace App\Controller;

use App\Entity\Mods;
use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\Template;
use App\Form\ModsType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ModsRepository;
use App\Repository\PartnerRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/mods')]
class ModsController extends AbstractController
{
    #[Route('/', name: 'app_mods')]
    public function index(ManagerRegistry $doctrine, ModsRepository $modsRepository, TemplateRepository $templateRepository): Response
    {    
        // tentative de récupération des modules utilisés dans un template
        $modules = $modsRepository->findAll();
        $templates = $templateRepository->findAll();
        $moduleUsedInTemplates = [];
        foreach($templates as $template)
        {
            $modsTemplate = $template->getModules();
            array_push($moduleUsedInTemplates, $modsTemplate);
        }

        // définis les modules non utilisés par les structures et les partenaires, rendans leur suppressions possibles
        // il manque à trier les modules non utilisés par les templates, pour l'instant echec
        $moduleUnused = [];
        foreach($modules as $module)
        {
            $partner = $module->getPartners();
            $structure = $module->getStructures();
            $template = $module->getTemplate();
            
           
            $test=[];
            if(count($partner) == 0 && count($structure) == 0)
            {
                array_push($moduleUnused, $module);
            }
        }

        $activeModules = $modsRepository->findBy(['is_active' => true]);   
        return $this->render('mods/index.html.twig', [
            'templates' => $templates,
            'modules' => $modules,
            'activeModules' => $activeModules,
            'modulesUnused' => $moduleUnused,
            'moduleUsedTemplate' => $moduleUsedInTemplates,
        ]);
    }

    #[Route('/new', name: 'new_module')]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response
    {
        $module = new Mods();

        $form = $this->createForm(ModsType::class, $module);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $module
                ->setCreatedAt(new \DateTime());

            $manager->persist($module);
            $manager->flush();

            return $this->redirectToRoute('app_mods');
        }

        return $this->renderForm('mods/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_module', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mods $module, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(ModsType::class, $module);
        $form->handleRequest(($request));
        
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($module);
            $em->flush();

            return $this->redirectToRoute('app_mods');
        }

        return $this->renderForm('mods/edit.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/delete/{id}', name: 'delete_module')]
    public function deleteAdmin(Mods $module, EntityManagerInterface $entityManager) : Response
    {  

        $entityManager->remove($module);
        $entityManager->flush();

        return $this->redirectToRoute('app_mods', [], Response::HTTP_SEE_OTHER);
    }

}