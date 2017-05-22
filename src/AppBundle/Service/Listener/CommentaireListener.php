<?php

namespace AppBundle\Service\Listener;

use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Category;
use AppBundle\Service\SlugService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class CommentaireListener
{
    private $slugService;
    private $authorizationChecker;

    public function __construct(\AppBundle\Service\Utils\SlugService $slugService, AuthorizationChecker $authorizationChecker){
        $this->slugService = $slugService;
        $this->authorizationChecker = $authorizationChecker;
    }

    /*evenement prepersist : evenement qui se déclenche à l'insert uniquement*/
    public function prePersist(Commentaire $commentaire, LifecycleEventArgs $eventArgs){

        $slug   =   $this->_generateSlug($commentaire->getUser()->getId().'_'.time());
        $commentaire->setSlug($slug);
    }

    /*evenement prepersist : evenement qui se déclenche à l'update*/
    public function preUpdate(Commentaire $commentaire, PreUpdateEventArgs $eventArgs){
        $entity = $eventArgs->getObject();

        $slug   =   $this->_generateSlug($commentaire->getUser()->getId().'_'.time());
        $commentaire->setSlug($slug);

    }

    public function preRemove(Commentaire $commentaire, LifecycleEventArgs $eventArgs){

    }

    private function _generateSlug($slug){
	    $changed    =   $this->slugService->generateSlug($slug);
	    return $changed;
    }


}