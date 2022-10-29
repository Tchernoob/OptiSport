<?php

namespace App\Security;

use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class OptiSportAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();
        
        // Si l'utilisateur connecté est admin, il est redirigé vers la page home d'OptiSport
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }
        
        // si l'utilisateur connecté est manager partenaire, il est redirigé vers la page de son partenaire
        if (in_array('ROLE_MANAGER', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_partner_show', [
                'id' => $token->getUser()->getPartner()->getId(),
            ]));
        }

        // si l'utilisateur connecté est manager structure, il est redirigé vers la page de sa structure
        if (in_array('ROLE_USER_STRUCTURE', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_structure_show', [
                'id' => $token->getUser()->getStructure()->getId(),
            ]));
        }
 
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
 
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    // public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    // {
    //     $user = $token->getUser();

    //     if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
    //         return new RedirectResponse($targetPath);
    //     }
        
        // if($this->User::class->getRoles()->hasRole('ROLE_ADMIN'))
        // {
        //     return new RedirectResponse($this->urlGenerator->generate('app_home'));
        // }

        // if($this->User::class->getRoles()->hasRole('ROLE_MANAGER'))
        // {
        //     return new RedirectResponse($this->urlGenerator->generate('app_partner'));
        // }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
    //     return new RedirectResponse($this->urlGenerator->generate('app_home'));
    // }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
