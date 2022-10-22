<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\User;
use App\Form\EventListener\UserFormSubscriber;
use App\Entity\Mods;
use App\Entity\Structure;
use App\Entity\Template;
use App\Repository\ModsRepository;
use App\Repository\TemplateRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class StructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('is_active', CheckboxType::class, [
                'label' => 'Structure Active',
                'data' => true,
                'required' => false,
            ])
            ->add('summary', TextType::class)
            ->add('description', TextareaType::class)
            ->add('url', TextType::class)
            ->add('logo', FileType::class, [
                'label' => 'logo',
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
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'multiple'=>false,
            ])
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'query_builder' => function (TemplateRepository $tr) {
                    return $tr->getTemplatesActive();
                },
                'multiple'=>false,
                'required'=>false,
            ])
            ->add('mods', EntityType::class,  [
                'class' => Mods::class,
                'query_builder' => function (ModsRepository $mr) use ($options) {
                    return $mr->findPartnerModsInactive($options['partner_id']);
                },
                'multiple'=>true,
                'required'=>false,
            ])
            ->add('user', UserType::class,
                ['label' => false])

            // ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
            'allow_extra_fields' => true, 
            'partner_id' => null
        ]);
    }
}
