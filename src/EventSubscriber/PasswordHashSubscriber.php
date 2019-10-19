<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordHashSubscriber implements EventSubscriberInterface
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * PasswordHashSubscriber constructor.
     *
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                'passwordHashEvent',
                EventPriorities::PRE_WRITE,
            ],
        ];
    }

    public function passwordHashEvent(ViewEvent $event)
    {
        $user   = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ( !$user instanceof User && Request::METHOD_POST !== $method ) {

            return;
        }

        // hash password
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPassword()));
        $event->setControllerResult($user);
    }
}
