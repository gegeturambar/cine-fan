<?php

namespace AppBundle\Service\Listener;

use AppBundle\Entity\Movie;
use AppBundle\Service\SlugService;
use AppBundle\Service\Utils\TranslateService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MovieListener
{
     private $slugService;

     private $authorizationChecker;

	private $translator;

	private $request;

	private $locales;

	private $locale;

	private $locale_currency;

	private $fold;

    public function __construct(\AppBundle\Service\Utils\SlugService $slugService, AuthorizationChecker $authorizationChecker, RequestStack $requestStack, TranslateService $translator, $locales, $locale, $locale_currency, $fold ){
        $this->slugService = $slugService;
        $this->authorizationChecker = $authorizationChecker;
	    $this->request = $requestStack->getCurrentRequest();
	    $this->translator = $translator;
	    $this->locales = $locales;
	    $this->locale = $locale;
	    $this->locale_currency = $locale_currency;
        $this->fold = $fold;
    }

    /*evenement prepersist : evenement qui se déclenche à l'insert uniquement*/
    public function prePersist(Movie $movie, LifecycleEventArgs $eventArgs){

        $slug           =$this->_generateSlug($movie->getTitle());
        $movie->setSlug($slug);

        //take care of price
	    $this->translateCurrency($movie);

	    $movie->setPublished( $this->authorizationChecker->isGranted('ROLE_ADMIN') ? $movie->getPublished() : false );
    }

    /*evenement prepersist : evenement qui se déclenche à l'update*/
    public function preUpdate(Movie $movie, PreUpdateEventArgs $eventArgs){

        $entity = $eventArgs->getObject();
        $slug           =$this->_generateSlug($movie->getTitle());
        $movie->setSlug($slug);

        $this->translateCurrency($movie);

        //verifie si le film existe pour remettre la photo lors de l'update
        $poster = $movie->getPicture();
        if(!$poster){
            $movie->setPicture($movie->oldPicture);
        }

    }

	/**
	 * @param Movie $movie
	 */
	protected function translateCurrency(Movie &$movie){

		$current = $this->request->getLocale();

		if($movie->getPrice() <= 0){
			return;
		}

		if($current == $this->locale){
			return;
		}

		foreach($this->locales as $loc){
			if($loc['code'] == $current){
				$movie->setPrice($this->translator->translateCurrency($movie->getPrice(),$this->locale_currency,$this->locales['currency']));
				break;
			}

		}

	}

    public function preRemove(Movie $movie, LifecycleEventArgs $eventArgs){
        if($movie->getPicture()!='')
            unlink($this->fold.'movie/'.$movie->getPicture());
    }

    private function _generateSlug($slug){
       $changed           = $this->slugService->generateSlug($slug);

        return $changed;
    }


}