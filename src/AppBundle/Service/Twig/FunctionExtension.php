<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 19/12/16
 * Time: 12:51
 */

namespace AppBundle\Service\Twig;


use AppBundle\Service\Basket\Basket;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class FunctionExtension extends \Twig_Extension
{
    private $twig;
    private $locales;
    private $request;
    private $router;
    private $session;
    private $doctrine;
    private $basket;

    public function __construct(\Twig_Environment $twig, RequestStack $request,Router $router, Session $session,Registry $doctrine,Basket $basket, array $locales){
    	$this->request = $request->getCurrentRequest();
        //$this->request = $request->getMasterRequest();
        $this->twig = $twig;
        $this->router = $router;
        $this->session = $session;
        $this->doctrine = $doctrine;
        $this->locales = $locales;
        $this->basket = $basket;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_locales',[$this,'renderLocales']),
            new \Twig_SimpleFunction('render_basket_total_price',[$this,'renderBasketTotalPrice'])
        ];
    }


    public function renderLocales($debug= false){

    	if(is_null($this->request))
    		return "";
        $route = $this->request->get('_route');

        $params = $this->request->get('_route_params');


        $routes = array();

        foreach($this->locales as $loc) {
            $routes[$loc['code']]['route'] = $this->router->generate($route, array_merge($params,array("_locale"=>$loc['code'])));
            $routes[$loc['code']]['flag'] = $loc['flag'];

        }
        return $this->twig->render('inc/render/locales.html.twig',array('locales'=>$this->locales,'routes'=>$routes));
    }


    public function renderBasketTotalPrice(){
        $total = $this->basket->getTotalPrice($this->basket->getBasket());
        if($total)
            return $total;
    }

}