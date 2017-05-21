<?php

namespace AppBundle\Service\Basket;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 28/12/16
 * Time: 11:48
 */
class Basket
{
    private $session;
    private $doctrine;

    public function __construct(Session $session, Registry $doctrine){
        $this->session = $session;
        $this->doctrine = $doctrine;
    }

    public function add($id){
        $basket = $this->getBasket();
        $basket[$id] = isset($basket[$id]) ? $basket[$id] + 1 : 1;
        $this->session->set('basket',$basket);
    }

    public function remove($id){
        $basket = $this->getBasket();
        $basket[$id] = isset($basket[$id]) ? $basket[$id] - 1 : 0;
        $this->session->set('basket',$basket);
    }

    public function getTotalPrice($basket=null){
        // Pour select
        $rc = $this->doctrine->getRepository("AppBundle:Movie");
        if(is_null($basket))
            $basket = $this->getBasket();

        if(!empty($basket))
            $records = $rc->getMoviesPriceById(array_keys($basket));
        else
            $records = array();
        $total = 0;

        foreach($records as $r){
            $total += $r['price'] * $basket[$r['id']];
        }
        return $total;
    }

    public function getBasket(){
        $basket = null;
        if($this->session->has('basket')){
            $basket = $this->session->get('basket');
        }else {
            $basket = array();
        }
        return $basket;
    }
}