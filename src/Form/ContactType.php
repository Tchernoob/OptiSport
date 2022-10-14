<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name',TextType::class, [
                'label' => 'Votre nom *', 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Champ obligatoire',
                    ]),
                    new Regex('/^[a-z0-9]+$/i', 'Caractères non autorisés (uniquement lettres et chiffres)')
                ],
            ])
            ->add('subject',TextType::class, [
                'label' => 'Objet du message *', 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Champ obligatoire',
                    ]),
                ],
                ])
            ->add('email',EmailType::class, [
                'label' => 'Votre mail *', 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Champ obligatoire',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 6],
                'label' => 'Message *', 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Champ obligatoire',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
