<?php

namespace App\Form\EventListener;

use App\Entity\HousingType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserFormSubscriber implements EventSubscriberInterface
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->em = $entityManager;
    } 

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA=> 'preSubmitData'
        ];
    }

    public function preSubmitData(FormEvent $event) : void
    {
        //dd($event->getData()->getUser()); 
        $user = $event->getData(); 
      //  dd($user); 
        $user->setCreatedAt(new \DateTime()); 
        $this->em->persist($user); 
        
    //    $event->setData($user); 
    }


}