<?php

namespace RememberMe\Event;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener implements EventSubscriberInterface
{
    const REMEMBER_ME_EXPIRATION_DAYS = 30;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::JWT_CREATED => 'onJWTCreated',
        ];
    }

    /**
     * @param JWTCreatedEvent $event
     * @throws \Exception
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request->getContentType() === 'json') {
            $data = json_decode($request->getContent(), true);

            if (!empty($data['_remember_me'])) {
                $expiration = new \DateTime('+' . self::REMEMBER_ME_EXPIRATION_DAYS . ' days');

                $payload        = $event->getData();
                $payload['exp'] = $expiration->getTimestamp();

                $event->setData($payload);
            }
        }
    }
}