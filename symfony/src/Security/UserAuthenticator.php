<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

// Gère l'authentification par formulaire (pseudo + mot de passe)
class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    // Nom de la route vers laquelle rediriger si l'utilisateur n'est pas connecté
    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    // Lit les données du formulaire de connexion et construit le passeport d'authentification
    public function authenticate(Request $request): Passport
    {
        // Récupère le pseudo et le mot de passe envoyés par le formulaire
        $pseudo = $request->getPayload()->getString('pseudo'); 
        $password = $request->getPayload()->getString('password');

        // Mémorise le pseudo dans la session pour le pré-remplir en cas d'erreur
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $pseudo);

        return new Passport(
            // Identifie l'utilisateur par son pseudo
            new UserBadge($pseudo),
            new PasswordCredentials($password),
            [
                // Vérifie le token CSRF pour se protéger des attaques cross-site
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                // Active la fonctionnalité "se souvenir de moi"
                new RememberMeBadge(),
            ]
        );
    }

    // Redirige l'utilisateur après une connexion réussie
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Si l'utilisateur essayait d'accéder à une page protégée, on l'y redirige
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Sinon, redirige vers la liste des personnages par défaut
        return new RedirectResponse($this->urlGenerator->generate('app_personnage_index'));
    }

    // Retourne l'URL de la page de connexion (utilisée par Symfony en cas d'accès refusé)
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}