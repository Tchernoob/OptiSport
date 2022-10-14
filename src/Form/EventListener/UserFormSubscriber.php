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
            FormEvents::PRE_SET_DATA=> 'preSetData'
        ];
    }

    public function preSetData(FormEvent $event) : void
    {
        $user = $event->getData();
        $user->setPassword(bin2hex(random_bytes(6))); 
        $user->setCreatedAt(new \DateTime()); 
        $event->setData($user); 
    }


}