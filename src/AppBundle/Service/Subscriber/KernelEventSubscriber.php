<?php
/**
 * Created by PhpStorm.
 * User: wamobi5
 * Date: 28/12/16
 * Time: 15:55
 */

namespace AppBundle\Service\Subscriber;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class KernelEventSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(){
        return [
//            KernelEvents::REQUEST => 'kernelRequest'//s'effectue à chaque request - des hooks comme les plugins joomla
            KernelEvents::RESPONSE => 'kernelResponse'//s'effectue à chaque RESPONSE - des hooks comme les plugins joomla
        ];
// VOIR http://symfony.com/doc/current/components/http_kernel.html#http-kernel-creating-listener
    }

    public function kernelRequest(GetResponseEvent $event){
        //mettre le site en maintenance
        $content = $this->twig->render('inc/maintenance.html.twig');
        $response = new Response($content, 503);
        return $event->setResponse($response);
    }

    public function kernelResponse(FilterResponseEvent $event){
        $content = $event->getResponse()->getContent();
        $statusCode = $event->getResponse()->getStatusCode();
//Protection du site en settant les headers
        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block'
        ];

        $response = new Response($content, $statusCode, $headers);
        return $event->setResponse($response);

    }

}