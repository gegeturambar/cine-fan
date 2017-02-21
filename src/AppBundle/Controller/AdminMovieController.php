<?php
/**
 * Created by PhpStorm.
 * User: Joachim
 * Date: 12/02/2017
 * Time: 10:08
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Form\MovieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminMovieController extends Controller
{
    /**
     * @Route("/add/movie", name="app.admin.movie.form")
     * @Route("/update/movie/{id}", name="app.admin.movie.form.update", requirements={"id" = "\d+"})
     */
    public function formAction(Request $request, $id=null)
    {
        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository('AppBundle:Movie');//select

        // creation d'un formulaire : on a l'entité et la class du formulaire... recette de cuisine !
        $entity = $id ? $rc->find($id) : new Movie();
        $entityType     = MovieType::class;



        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);//récupération de la saisie

        //Service de gestion du formulaire
        $movieHandler = $this->get('app.service.handler.moviehandler');

        if($movieHandler->check($form)){

            $movieHandler->process();

//            $translate  = $this->get('translator');
//            $add        = $id ? $translate->trans('form.movie.message.update') : $translate->trans('form.movie.message.ajout');

            $add        = $id ? 'Le film a bien été modifié' : 'Le film a bien été ajouté';
            $this->addFlash('success', $add);


            return $this->redirectToRoute('app.main.search');
        }


        //envoi du formulaire sous forme de vue

        return $this->render('admin-movie/form.html.twig', [
            'form'=>$form->createView(),
            'movie' => $entity
        ]);
    }

    /**
     * @Route("/movie/delete/{id}", name="app.admin.movie.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {


        //Service de gestion du formulaire
        $movieHandler = $this->get('app.service.handler.moviehandler');
        $movieHandler->delete($id, 'AppBundle:Movie' );

//        $translate  = $this->get('translator');
//        $delete     = $translate->trans('form.movie.message.delete');
        $delete     = 'Film supprimé avec succès';

        $this->addFlash('success', $delete);

        return $this->redirectToRoute('app.main.search');
    }
}