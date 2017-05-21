<?php

namespace AppBundle\Service\Listener;

use AppBundle\Entity\Actor;
use AppBundle\Entity\Category;
use AppBundle\Service\SlugService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class ActorListener
{
    private $slugService;
    private $fold;
    private $doctrine;
    private $authorizationChecker;

    public function __construct(Registry $doctrine, \AppBundle\Service\Utils\SlugService $slugService, AuthorizationChecker $authorizationChecker, $fold ){/*on fait un construct quand il appelle un parametre qui est passé au constructeur*/
        $this->slugService = $slugService;
        $this->doctrine = $doctrine;
        $this->authorizationChecker = $authorizationChecker;
        $this->fold = $fold;
    }

    /*evenement prepersist : evenement qui se déclenche à l'insert uniquement*/
    public function prePersist(Actor $actor, LifecycleEventArgs $eventArgs){

        $slug           =$this->_generateSlug($actor->getNom());
        $actor->setAlias($slug);

	    $actor->setPublished( $this->authorizationChecker->isGranted('ROLE_ADMIN') ? $actor->getPublished() : false );

        //$this->_categsLink($actor);

    }

    /*evenement prepersist : evenement qui se déclenche à l'update*/
    public function preUpdate(Actor $actor, PreUpdateEventArgs $eventArgs){


        $entity = $eventArgs->getObject();
        //dump($entity);
        $slug           =$this->_generateSlug($actor->getNom());
        $actor->setAlias($slug);

        //verifie si le film existe pour remettre la photo lors de l'update
        $poster = $actor->getImage();
        if(!$poster){
            $actor->setImage($actor->oldPoster);
        }

        //$this->_categsLink($actor);

    }

    public function preRemove(Actor $actor, LifecycleEventArgs $eventArgs){
        if($actor->getImage()!='')
            unlink($this->fold.'actor/'.$actor->getImage());
    }

    private function _generateSlug($slug){
        $changed           = $this->slugService->generateSlug($slug);

        return $changed;
    }


    // this is insane >___<"
    private function _categsLink($actor){

//        print_r($actor->getCategories());die();

        $rc  = $this->doctrine->getRepository('AppBundle:Movie');/*select*/
        $rc2 = $this->doctrine->getRepository('AppBundle:Category');


        foreach($actor->getCategories() as $categ){
            $actor->removeCategory($categ);
        }
//            print_r($actor->getCategories());
//        die();
//        $em = $this->doctrine->getManager();/*Tout sauf select*/
//        $em->persist($actor);
//        $em->flush();
//        die();



//        $actor->setCategories(new ArrayCollection()) ;//le set n'existe pas


        foreach($actor->getMovies() as $movie){
            $categ = $rc->categsByFilmId($movie->getId());

            $actor->getCategories()->add($rc2->find($categ));
//            $actor->addCategory($rc2->find($categ));
        }

    }

}