<?php

namespace AppBundle\Service\Listener;

use AppBundle\Entity\Category;
use AppBundle\Service\SlugService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\PostPersist;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class CategoryListener
{
	private $slugService;

	private $authorizationChecker;

    public function __construct(\AppBundle\Service\Utils\SlugService $slugService, AuthorizationChecker $authorizationChecker ){/*on fait un construct quand il appelle un parametre qui est passé au constructeur*/
        $this->slugService = $slugService;
        $this->authorizationChecker = $authorizationChecker;
    }

    /*evenement prepersist : evenement qui se déclenche à l'insert*/
    public function prePersist(Category $category, LifecycleEventArgs $eventArgs){

        $slug           =$this->_generateSlug($category->getName());
        $category->setSlug($slug);
	    $category->setPublished( $this->authorizationChecker->isGranted('ROLE_ADMIN') ? $category->getPublished() : false );
    }

    /*evenement prepersist : evenement qui se déclenche à l'update*/
    public function preUpdate(Category $category, PreUpdateEventArgs $eventArgs){
        $entity = $eventArgs->getObject();
        //dump($entity);
        $slug           =$this->_generateSlug($category->getName());
        $category->setSlug($slug);
    }

    private function _generateSlug($slug){
       $changed           = $this->slugService->generateSlug($slug);

        return $changed;
    }


}