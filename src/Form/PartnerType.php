<?php

namespace App\Form;

use App\Entity\Mods;
use App\Entity\Partner;
use App\Entity\Template;
use App\Entity\User;
use App\Form\EventListener\UserFormSubscriber;
use App\Repository\ModsRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnerType extends AbstractType
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager; 
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('is_active', CheckboxType::class, [
                'label' => 'Partenaire Actif',
                'data' => true,
                'required' => false,
            ])
            ->add('summary', TextType::class, [
                'label' => 'Sommaire'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description Complète'
            ])
            ->add('url', TextType::class, [
                'label' => 'Lien Url vers le Partenaire'
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo de la Structure',
                'label_attr' => array('class' => 'btn btn-dark '),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid file extension',
                    ])
                ],
            ])
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'query_builder' => function (TemplateRepository $tr) {
                    return $tr->getTemplatesActive();
                },
                'multiple' =>false,
                'required' =>false,
            ])
            ->add('user', UserType::class,
                ['label' => false])

            // ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
            'allow_extra_fields' => true 
        ]);
    }
}
