<?php
/**
 * Created by PhpStorm.
 * User: wamobi5
 * Date: 22/12/16
 * Time: 09:41
 */

namespace AppBundle\Service\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ActorFormSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // doit retourner un tableau d'evenement
        return[
            FormEvents::POST_SET_DATA => 'postSetData' // POST_SET_DATA : evenemnt qui se déclenche une fois que le formulaire est affiché et rempli
        ];
    }
//$event recupere le $builder de buildForm de Type
    public function postSetData( FormEvent $event){
//        dump('ok');die();
        $form = $event->getForm();

        //saisie
        $data = $form->getData();

        //entité - ce qu'il y'a dans la base
        $entity = $event->getData();

        /*dump($form, $data, $entity);*/

        //si Insert
        $constraints = $entity->getId() ? [] : [
            new NotBlank(['message'=> 'actor.image.notblank']),
            new Image([
                    'mimeTypes'=>['image/jpeg'],
                    'mimeTypesMessage'=>'actor.image.mimetypes'
                ]
            )
        ];

        //ajout du champ poster ici pour ne mettre des contraintes qu'en insert

        $form
            ->add('image', FileType::class, [
                'data_class' => null,
                'constraints'=> $constraints
            ])
        ;

        $entity->oldPoster = $entity->getImage();

    }

}