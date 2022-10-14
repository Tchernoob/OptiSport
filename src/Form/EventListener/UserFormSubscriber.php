<?php

namespace App\Form\EventListener;

use App\Entity\HousingType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


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
        $user->setRoles(['ROLE_MANAGER']);
        $user->setPassword(bin2hex(random_bytes(16))); 
        $user->setCreatedAt(new \DateTime()); 
        $event->setData($user); 
    }


}