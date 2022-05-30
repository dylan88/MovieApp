<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class RecordLastLoginDateSubscriber implements EventSubscriberInterface
{
    public function onLoginSuccessEvent(LoginSuccessEvent $event)
    {
        /* @var \App\Entity\User */
        $user = $event->getUser();
        $user->setLastLoginDate(new \DateTime());
        // ...
    }

    public static function getSubscribedEvents() : array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccessEvent',
        ];
    }
}
