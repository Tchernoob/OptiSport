<?php

namespace App\Form;

use App\Entity\ClientMods;
use App\Entity\Mods;
use App\Entity\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('is_active', CheckboxType::class, [
                'label' => 'Module Disponible',
                'data' => true,
                'required' => false,
            ])
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'multiple' =>false,
                'required' =>false,
                'mapped' => false
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mods::class,
        ]);
    }
}