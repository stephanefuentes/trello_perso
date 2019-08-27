<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Services\StringService;

class UserEntityListener
{
    private $encoder;
    private $stringService;
    private $twig;
    private $mailer;

    public function __construct(UserPasswordEncoderInterface $encoder, StringService $s, \Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->encoder = $encoder; // Service pour hasher les mot de passe
        $this->stringService = $s; // Service de generation des token
        $this->twig = $twig; // Utilisation des template twig
        $this->mailer = $mailer; // Swiftmailer
    }

    public function prePersist(User $user, LifecycleEventArgs $args)
    {
        $user->setToken($this->stringService->generateToken(64));
        $user->setIsActive(false);

        $password = $user->getPassword();
        $hash = $this->encoder->encodePassword($user, $password);
        $user->setPassword($hash);
    }


    public function postPersist(User $user, LifecycleEventArgs $args)
    {

        $message = (new \Swift_Message('Confirmation d inscription'))
            ->setFrom('contact@website.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'email/confirm.html.twig',
                    [
                        'token' => $user->getToken()
                    ]
                ),
                'text/html'
            );

        // envoi du mail
        $this->mailer->send($message);
    }
}
