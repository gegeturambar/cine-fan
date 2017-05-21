<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 22/12/16
 * Time: 09:40
 */

namespace AppBundle\Service\Subscriber;


use AppBundle\Event\OrderEvents;
use AppBundle\Event\OrderFinalisationEvent;
use AppBundle\Service\Basket\Basket;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;
use Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderEventsSubscriber implements EventSubscriberInterface
{

    private $snappy;
    private $twig;
    private $basket;
    private $doctrine;

    public function __construct(LoggableGenerator $snappy, \Twig_Environment $twig_Environment, Basket $basket ,Registry $doctrine ){
        $this->snappy = $snappy;
        $this->twig = $twig_Environment;
        $this->basket = $basket;
        $this->doctrine = $doctrine;
    }

    public static function getSubscribedEvents()
    {
        return [
            OrderEvents::ORDER_FINALISATION => 'orderFinalisation'
        ];
    }

    public function orderFinalisation(OrderFinalisationEvent $event){
        $pdf_name = "order-".$event->getUserId()."-".time().'.pdf';

        $rc = $this->doctrine->getRepository("AppBundle:Movie");
        $basket = $event->getBasket();

        $records = $rc->getMoviesById(array_keys($basket));

        $totalPrice = $this->basket->getTotalPrice($event->getBasket());
        $html = $this->twig->render(
            'order/ordertopdf.html.twig',
            array(
                'total_price'  => $totalPrice,
                'records'   =>  $records,
                'basket'    =>  $basket
            )
        );

        try {
            $this->snappy->generateFromHtml($html, 'upload/'.$pdf_name);
        }catch(\Exception $ex){
            //echo $ex->getMessage();
        }
        $event->setPdfName($pdf_name);

    }
}
