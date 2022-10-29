<?php

namespace App\Form;

use App\Entity\Mods;
use App\Entity\TemplateMods;
use App\Entity\Template;
use Doctrine\ORM\EntityRepository;
use App\Repository\ModsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du Package',
                'label_attr' => [
                    'class' => 'label-template'
                ],
                'attr' => [
                    'class' => 'user-input'
                ]])
            ->add('mods', EntityType::class, [
                'label' => "Modules Package :",
                'label_attr' => array('class' => 'label-edit-structure'),
                'class' => Mods::class,
                'query_builder' => function (ModsRepository $mr) {
                    return $mr->getModsActive();
                },
                'choice_label' => 'name',
                'multiple' =>true,
                'expanded' => true,
                'required' =>false,
            ])
            ->add('Ajouter', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success btn-lg btn-template'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
        ]);
    }
}
