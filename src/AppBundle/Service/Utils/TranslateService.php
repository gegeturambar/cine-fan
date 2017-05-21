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
use Symfony\Component\HttpKernel\Kernel;

class TranslateService
{
	private $kernel;

	public function __construct(Kernel $kernel)
	{
		$this->kernel = $kernel;
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
	    $path = $this->kernel->getRootDir()."/Resources/translations/$className.".$lang.".yml";

	    /*
	    $fs = new Filesystem();
	    if(!$fs->exists($path)){
		    $fs->touch($path);
	    }*/

	    $ct = "$key: ".$translation;
	    file_put_contents($path,$ct);
	    // clear cache
	    exec($this->kernel->getRootDir()."/bin/console cache:clear");
    }
}