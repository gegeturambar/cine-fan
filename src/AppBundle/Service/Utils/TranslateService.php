<?php
/**
 * Created by PhpStorm.
 * User: wamobi5
 * Date: 19/12/16
 * Time: 12:50
 */

namespace AppBundle\Service\Utils;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Yaml\Yaml;

class TranslateService
{
	private $kernel;

	private $translator;

	private $session;

	private $request;
	private  $locales;
	private  $locale;
	private  $localeCurrency;

	public function __construct(Kernel $kernel, Translator $translator, RequestStack $request, Session $session, array $locales, $locale, $localeCurrency)
	{
		$this->translator = $translator;
		$this->kernel = $kernel;
		$this->request = $request->getCurrentRequest();
		$this->session = $session;
		$this->locales = $locales;
		$this->locale = $locale;
		$this->localeCurrency = $localeCurrency;
	}

	function translatePriceProxy($price, $fromLocaleToCurrent = true){
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
				if($fromLocaleToCurrent)
					return $this->translatePrice($price,$currentCurrency,$this->localeCurrency['currency']);
				else
					return $this->translatePrice($price,$this->localeCurrency['currency'],$currentCurrency);
			}
		}
	}

	function translatePriceFromCurrentToLocale($value){
		return $this->translatePriceProxy($value,false);
	}

	function translatePriceFromLocaleToCurrent($value){
		return $this->translatePriceProxy($value);
	}

	function translatePrice($value, $to, $from)
	{

		// check first from Session, if not, check from api
		$currencies = $this->session->get('currencies');

		if (!isset($currencies))
			$currencies = array();
		if ((array_key_exists($to, $currencies) && array_key_exists($from, $currencies[$to])) || (array_key_exists($from, $currencies) && array_key_exists($to, $currencies[$from])))
		{
			if ((array_key_exists($from, $currencies) && array_key_exists($to, $currencies[$from])))
			{
				// multiply
				return $value * $currencies[$from][$to];
			}
			elseif (array_key_exists($to, $currencies) && array_key_exists($from, $currencies[$to]))
			{
				//divide
				return $value / $currencies[$to][$from];
			}
		}

		$url = "http://api.fixer.io/latest?symbols=$to,$from";
		$ch  = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		$data     = json_decode($response);

		if ($data->rates)
		{
			$currencies[$from][$to]=$data->rates->$to;
			$this->session->set('currencies',$currencies);
			return $value * $currencies[$from][$to];
		}else
				return false;
		}

	function translate($value,$to,$from){

	    $url = "http://www.transltr.org/api/translate?";
	    $url .= "text=$value&to=$to&from=$from";
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $response = curl_exec($ch);
	    $data = json_decode($response);
	    if($data->translationText)
	        return $data->translationText;
	    else
	    	return false;
    }

    function addTranslate($className,$lang,$key,$translation){

		// check if necessary to add translation

	    $path = $this->kernel->getRootDir()."/Resources/translations/$className.".$lang.".yml";
	    if(!file_exists($path)){
	    	$handle = fopen($path,'w+');
	    	fclose($handle);
	    }
	    $translations = Yaml::parse(file_get_contents($path));
	    $translations[$key] = $translation;
	    $yaml = Yaml::dump($translations);
	    file_put_contents($path,$yaml);

	    // clear cache
	    exec($this->kernel->getRootDir()."/bin/console cache:clear");
    }
}