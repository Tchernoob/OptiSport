<?php

namespace App\Form;

use App\Entity\Mods;
use App\Entity\Structure;
use App\Repository\ModsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StructureEditRightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mods', EntityType::class,  [
                'label' => "Modules Structure :",
                'class' => Mods::class,
                'query_builder' => function (ModsRepository $mr) use ($options) {
                    return $mr->findPartnerModsInactive($options['partner_id']);
                },
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])

            ->add('Modifier', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success btn-lg btn-template'
                ]
            ])
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