<?php

namespace AppBundle\Service\Listener;

use AppBundle\Entity\Movie;
use AppBundle\Service\SlugService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MovieListener
{
     private $slugService;
     private $authorizationChecker;
     private $fold;

    public function __construct(\AppBundle\Service\Utils\SlugService $slugService, AuthorizationChecker $authorizationChecker, $fold ){
        /*on fait un construct quand il appelle un parametre qui est passé au constructeur*/
        $this->slugService = $slugService;
        $this->authorizationChecker = $authorizationChecker;
        $this->fold = $fold;
    }

    /*evenement prepersist : evenement qui se déclenche à l'insert uniquement*/
    public function prePersist(Movie $movie, LifecycleEventArgs $eventArgs){

        $slug           =$this->_generateSlug($movie->getTitle());
        $movie->setSlug($slug);
	    $movie->setPublished( $this->authorizationChecker->isGranted('ROLE_ADMIN') ? $movie->getPublished() : false );
    }

    /*evenement prepersist : evenement qui se déclenche à l'update*/
    public function preUpdate(Movie $movie, PreUpdateEventArgs $eventArgs){


        $entity = $eventArgs->getObject();
        $slug           =$this->_generateSlug($movie->getTitle());
        $movie->setSlug($slug);

        //verifie si le film existe pour remettre la photo lors de l'update
        $poster = $movie->getPicture();
        if(!$poster){
            $movie->setPicture($movie->oldPicture);
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