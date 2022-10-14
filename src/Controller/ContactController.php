<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;


class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact')]

    public function index(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) { 

            $contactFormData = $form->getData();
            
            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('soraya@test.com')
                ->subject($contactFormData['subject'])
                ->text('Sender : '.$contactFormData['name'].\PHP_EOL.
                    $contactFormData['message'],
                    'text/plain');
            $mailer->send($message);

            $this->addFlash('success', 'Votre message a bien été envoyé. Nous reviendrons vers vous dans les plus brefs délais !');
         
            $form = $this->createForm(ContactType::class);
            
        }

        return $this->renderForm('contact/form.html.twig', [
            'form' => $form
        ]);
    }
    
}
