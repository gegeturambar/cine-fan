<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 22/12/16
 * Time: 09:40
 */

namespace AppBundle\Service\Subscriber;


use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuthentificationSubscriber implements EventSubscriberInterface
{

    private $doctrine;
    private $session;
    private $mailer;
    private $max_authentication_failure;

    public function __construct(Registry $doctrine,Session $session,\Swift_Mailer $mailer,$maxAuthentificationFailure){
        $this->doctrine = $doctrine;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->max_authentication_failure = $maxAuthentificationFailure;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'authentication_fail',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'authentication_success',
        ];
    }

    public function authentication_fail(AuthenticationEvent $event){
        $session = $this->session;

        if($session->has('failed_connect_count')){
            if($session->get('failed_connect_count') > $this->max_authentication_failure){
                $message = \Swift_Message::newInstance()
                    ->setTo('9b8f91a58e-b441e5@inbox.mailtrap.io')
                    ->setFrom('send@example.com')
                    ->setSubject('Hello Email')
                    ->setBody('fail')
                ;

                $this->mailer->send($message);
                $session->remove('failed_connect_count');

            }
            $session->set('failed_connect_count', $session->get('failed_connect_count') +1 );
        }else{
            $session->set('failed_connect_count',1);
        }
    }

    public function authentication_success(AuthenticationEvent $event){
        $user = $event->getAuthenticationToken()->getUser();

        if( $user instanceof User ) {
            $rc = $this->doctrine->getRepository("AppBundle:User");
            $user = $rc->find($user->getId());
            $user->setLastConnection(new \DateTime());
            $mn = $this->doctrine->getManager();
            $mn->persist($user);
            $mn->flush();
        }
    }

}
