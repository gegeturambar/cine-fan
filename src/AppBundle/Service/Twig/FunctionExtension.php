<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 19/12/16
 * Time: 12:51
 */

namespace AppBundle\Service\Twig;


use AppBundle\Service\Basket\Basket;
use AppBundle\Service\Utils\TranslateService;
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
    private $translateService;
    private $basket;
    private $localeCurrency;
	private $locale;

    public function __construct(\Twig_Environment $twig, RequestStack $request,Router $router, Session $session,Registry $doctrine, TranslateService $translateService, Basket $basket, array $locales, $locale, $localeCurrency){
    	$this->request = $request->getCurrentRequest();
        //$this->request = $request->getMasterRequest();
        $this->twig = $twig;
        $this->router = $router;
        $this->session = $session;
        $this->doctrine = $doctrine;
        $this->translateService = $translateService;
        $this->locales = $locales;
        $this->basket = $basket;
        $this->locale = $locale;
        $this->localeCurrency = $localeCurrency;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_locales',[$this,'renderLocales']),
            new \Twig_SimpleFunction('render_basket_total_price',[$this,'renderBasketTotalPrice']),
	        new \Twig_SimpleFunction('render_locale_currency',[$this,'renderLocaleCurrency']),
	        new \Twig_SimpleFunction('translate_price',[$this,'translatePrice'])
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

	public function renderLocaleCurrency($debug= false){
		if(is_null($this->request))
			return "";
		$current_locale = $this->request->getLocale();
		foreach ($this->locales as $locale)
		{
			if($locale['code'] == $current_locale){
				return $locale['currency_symbol'];
			}
		}
	}

	public function translatePrice($price){
		if(is_null($this->request))
			return $price;
		$currentLocale = $this->request->getLocale();
		if($this->locale == $currentLocale)
			return $price;

		// get currentCurrency
		foreach ($this->locales as $locale)
		{
			if($locale['code'] == $currentLocale){
				$currentCurrency = $locale['currency'];
				return $this->translateService->translatePrice($price,$currentCurrency,$this->localeCurrency['currency']);
			}
		}
	}

    public function renderBasketTotalPrice(){
        $total = $this->basket->getTotalPrice($this->basket->getBasket());
        if($total)
            return $total;
    }

}