<?php

namespace App\Controller;

use App\Entity\Template;
use App\Entity\TemplateMods;
use App\Form\TemplateType;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/template')]
class TemplateController extends AbstractController
{
    #[Route('/', name: 'app_template')]
    public function index(TemplateRepository $templateRepository): Response
    {
        // if (!$this->isGranted('ROLE_ADMIN')) 
        // {
        //     throw $this->createAccessDeniedException('Vous ne posséder pas les droits pour accéder à cette page');
        // }

        $templates = $templateRepository->findAll();
        

        $templateUnused = [];
        foreach($templates as $template)
        {
            $partner = $template->getPartners();
            $structure = $template->getStructures();
            
            // si un template est inactif tant chez les partenaires que chez les structures, on l'enregistre dans le tableau
            // contenant les templates non utilisés  
            if (count($partner) == 0 && count($structure) == 0) {
                array_push($templateUnused, $template);
            }
        }

        
        $activeTemplates = $templateRepository->findBy(['is_active' => true]);
        $notActiveTemplates = $templateRepository->findBy(['is_active' => false]);
        return $this->render('template/index.html.twig', [
            'templates' => $templates,
            'activeTemplates' => $activeTemplates,
            'notActiveTemplates' => $notActiveTemplates,
            'templateUnused' => $templateUnused,
        ]);
    }

    #[Route('/new', name: 'new_template')]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response
    {
        $template = new Template();

        $form = $this->createForm(TemplateType::class, $template);

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            $template
                ->setCreatedAt(new \DateTime())
                ->setIsActive(true);

            $manager->persist($template);
            $manager->flush();

            return $this->redirectToRoute('app_template');
        }

        return $this->renderForm('template/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_template', methods: ['GET', 'POST'])]
    public function edit(Request $request, Template $template, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest(($request));
        
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($template);
            $em->flush();

            return $this->redirectToRoute('app_template');
        }

        return $this->renderForm('template/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/filter/{val}', name: 'filter_template', methods: ['GET'])]
    public function filterPartner(TemplateRepository $tr, $val) : JsonResponse
    {
     
        $templateFiltered = $tr->findByCriteria($val);

        $result = []; 
       
        foreach($templateFiltered as $template)
        {
            $date = $template->getcreatedAt()->format('d-m-Y');
            $modules = $template->getModules();
            $countMods = count($modules);

            $data =
            [
                'id' => $template->getId(), 
                'name' => $template->getName(),
                'status' => $template->isIsActive(), 
                'createdAt' => $date,
                'modules' => $countMods,
            ];

            $result[] = $data; 
        }
        return new JsonResponse($result);
    }

    #[Route('/delete/{id}', name: 'delete_template', methods: ['POST'])]
    public function deleteAdmin(Template $template, EntityManagerInterface $entityManager) : Response
    {        
        $entityManager->remove($template);
        $entityManager->flush();

        return $this->redirectToRoute('app_template', [], Response::HTTP_SEE_OTHER);
    }
}
