<?php

namespace AppBundle\Service\Listener;

use AppBundle\AppBundle;
use AppBundle\Entity\Tag;
use AppBundle\Service\SlugService;
use AppBundle\Service\Utils\TranslateService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\PostPersist;
use Eko\GoogleTranslateBundle\Translate\Method\Translator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class TagListener
{
	private $slugService;

	private $authorizationChecker;

	private $translator;

	private $request;

	private $locales;

	private $locale;

    public function __construct(\AppBundle\Service\Utils\SlugService $slugService, AuthorizationChecker $authorizationChecker, RequestStack $requestStack, TranslateService $translator, $locales, $locale ){
        $this->slugService = $slugService;
        $this->authorizationChecker = $authorizationChecker;
	    $this->request = $requestStack->getCurrentRequest();
        $this->translator = $translator;
        $this->locales = $locales;
        $this->locale = $locale;
    }

    /*evenement prepersist : evenement qui se déclenche à l'insert*/
    public function prePersist(Tag $tag, LifecycleEventArgs $eventArgs){

	    $this->translate($tag);

	    //$slug           =$this->_generateSlug($tag->getName());
        // $tag->setSlug($slug);
	    $tag->setPublished( $this->authorizationChecker->isGranted('ROLE_ADMIN') ? $tag->getPublished() : false );

    }

    /*evenement prepersist : evenement qui se déclenche à l'update*/
    public function preUpdate(Tag $tag, PreUpdateEventArgs $eventArgs){
        $entity = $eventArgs->getObject();
        //dump($entity);
	    $this->translate($tag);
        //$slug           =$this->_generateSlug($tag->getName());
        //$tag->setSlug($slug);
    }

    private function _generateSlug($slug){
       $changed           = $this->slugService->generateSlug($slug);
        return $changed;
    }


	/**
	 * @param Tag $tag
	 */
	protected function translate(Tag &$tag){
    	// get current language
		$current = $this->request->getLocale();
		// set the tag name in english, and translate

		$translate_locale = $tag->getSlug();
		if($current != $this->locale){
			// translate in locale the name for the slug
			$translate_locale = $this->translator->translate($tag->getSlug(),$this->locale,$current);
		}

		$slug = $this->_generateSlug($translate_locale);
		$tag->setSlug($slug);

		$this->translator->addTranslate('tag',$this->locale,$slug,$translate_locale);

    	foreach($this->locales as $loc){
			$code =$loc['code'];
    		if( $code != $this->locale ){
			    $translation = $this->translator->translate($translate_locale,$code,$this->locale);
			    $this->translator->addTranslate('tag',$code,$slug,$translation);
		    }
	    }
	}

}