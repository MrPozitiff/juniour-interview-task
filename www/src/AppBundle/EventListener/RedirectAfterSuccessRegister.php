<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 030 30.10.17
 * Time: 13:44
 */

namespace AppBundle\EventListener;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class RedirectAfterSuccessRegister implements EventSubscriberInterface
{

    use TargetPathTrait;
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegisterSuccess'
        ];
    }

    public function onRegisterSuccess(FormEvent $event){
        $url = $this->getTargetPath($event->getRequest()->getSession(), 'main');

        if (!$url){
            $url = $this->router->generate('homepage');
        }

        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }


}