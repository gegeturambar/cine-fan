<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/12/16
 * Time: 09:53
 */

namespace AppBundle\Controller;

use AppBundle\Event\OrderEvents;
use AppBundle\Event\OrderFinalisationEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/basket")
 */
class OrderController extends Controller
{

    /**
     * @Route("/order/finalisation", name="app.order.finalisation")
     */
    public function finalisationAction(Request $request){


        $orderFinalisationEvent = new OrderFinalisationEvent();
        $basket = $this->get('app.services.basket');
        $orderFinalisationEvent->setBasket($basket->getBasket());

        $orderFinalisationEvent->setUserEmail($this->getUser()->getEmail());
        $orderFinalisationEvent->setUserId($this->getUser()->getId());

        $eventDispatcher = $this->get('event_dispatcher');

        $eventDispatcher->dispatch(OrderEvents::ORDER_FINALISATION, $orderFinalisationEvent);

        $pdf_name = $orderFinalisationEvent->getPdfName();
        return $this->render('order/index.html.twig',[
            "pdffile"=>$pdf_name
        ]) ;
    }

}