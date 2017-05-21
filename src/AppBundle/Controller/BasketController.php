<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/12/16
 * Time: 09:53
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/basket")
 */
class BasketController extends Controller
{

    /**
     * @Route("/basket", name="app.basket.view")
     */
    public function viewAction(Request $request){

        $doctrine = $this->getDoctrine();

        // pour INSERT, DELETE, UPDATE
        $em  = $doctrine->getManager();
        // Pour select
        $rc = $doctrine->getRepository("AppBundle:Movie");

        $basket = $this->get('app.services.basket')->getBasket();

        $records = $rc->getMoviesById(array_keys($basket));

        return $this->render('basket/index.html.twig', array('records'=>$records,'basket'=>$basket)) ;
    }

    /**
     * @Route("/basket/remove/{id}", name="app.basket.remove",requirements={"id"="\d+"})
     */
    public function removeAction(Request $request,$id ){

        $id = $request->isXmlHttpRequest() ? $request->request->get('id') : $id;
        $this->get('app.services.basket')->remove($id);

        return new JsonResponse(array('success'=>true)
        );
    }

    /**
     * @Route("/basket/add/{id}", name="app.basket.add",requirements={"id"="\d+"})
     */
    public function addAction(Request $request,$id ){

        $id = $request->isXmlHttpRequest() ? $request->request->get('id') : $id;

        $this->get('app.services.basket')->add($id);

        return new JsonResponse(array('success'=>true)
        );
        //return $this->render('movie/index.html.twig', array('mov'=>$record)) ;
    }
}