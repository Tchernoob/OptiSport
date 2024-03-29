<?php

namespace App\Form;

use App\Entity\User;
use App\Form\EventListener\UserFormSubscriber;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager; 
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email Utilisateur',
                'label_attr' => [
                    'class' => 'user-label'
                ],
                'attr' => [
                    'class' => 'user-input'
                ]])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                'class' => 'user-label'
            ],
            'attr' => [
                'class' => 'user-input'
            ]])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                'class' => 'user-label'
            ],
            'attr' => [
                'class' => 'user-input'
            ]])

            ->add('Ajouter', SubmitType::class, [
                'attr' => [
                'class' => 'btn btn-outline-success btn-lg btn-user' 
            ]])
        ;

        $builder->addEventSubscriber(new UserFormSubscriber($this->em));
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
        ]);
    }
}
