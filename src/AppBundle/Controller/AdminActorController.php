<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Actor;
use AppBundle\Form\ActorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/*ANNOTATION AU DESSUS DE LA CLASS : VALABLE POUR TOUTE LA CLASS*/
/**
 * @Route("/admin/actor")
 */

class AdminActorController extends Controller
{
    /**
     * @Route("/", name="app.admin.actor.index")
     */
    public function indexAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();/*Tout sauf select*/
        $rc = $doctrine->getRepository('AppBundle:Actor');/*select*/


        // replace this example code with whatever you need
        return $this->render('admin-actor/index.html.twig', [
            'actors' => $rc->findAll()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app.admin.actor.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {

        //Service de gestion du formulaire
        $formHandler = $this->get('app.service.handler.formhandler');
        $formHandler->delete($id, 'AppBundle:Actor' );

        $translate  = $this->get('translator');
        $delete     = $translate->trans('form.actor.message.delete');

        $this->addFlash('success', $delete);

        return $this->redirectToRoute('app.admin.actor.index');
    }

    /**
     * @Route("/form", name="app.admin.actor.form")
     * @Route("/form/update/{id}", name="app.admin.actor.form.update", requirements={"id" = "\d+"})
     */
    public function formAction(Request $request, $id=null)
    {
        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository('AppBundle:Actor');//select

        // creation d'un formulaire : on a l'entité et la class du formulaire... recette de cuisine !
        $entity = $id ? $rc->find($id) : new Actor();
        $entityType     = ActorType::class;



        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);//récupération de la saisie

        //Service de gestion du formulaire
        $actorHandler = $this->get('app.service.handler.actorhandler');

        if($actorHandler->check($form)){

            $actorHandler->process();
            $translate  = $this->get('translator');

            $add        = $id ? $translate->trans('form.actor.message.update') : $translate->trans('form.actor.message.ajout');
            $this->addFlash('success', $add);
            return $this->redirectToRoute('app.admin.actor.index');
        }


        //envoi du formulaire sous forme de vue

        return $this->render('admin-actor/form.html.twig', [
            'form'=>$form->createView()
        ]);
    }

}
