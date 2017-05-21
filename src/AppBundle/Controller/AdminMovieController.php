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
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class AdminMovieController extends Controller
{

	/**
	 * @Route("/admin/movie", name="app.admin.movie.index")
	 */
	public function indexAction(Request $request)
	{
		$doctrine = $this->getDoctrine();
		// Pour select
		$rc = $doctrine->getRepository("AppBundle:Movie");
		$records = $rc->findAll();

		// replace this example code with whatever you need
		return $this->render('admin/movie/index.html.twig', ['records'=>$records
		]);
	}


	/**
	 * @Route("/movie/add", name="app.movie.form")
     * @Route("/admin/movie/add", name="app.admin.movie.form")
     * @Route("/admin/movie/update/{id}", name="app.admin.movie.form.update", requirements={"id" = "\d+"})
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

        if($movieHandler->check($form))
        {
	        $translate = $this->get('translator');
	        try
	        {
		        $movieHandler->process();
	        }
	        catch (UniqueConstraintViolationException $exception)
	        {
		        $this->addFlash('warning', $translate->trans('movie.flash_messages.add_fail_already_existed'));

		        return $this->redirect($request->getUri());
	        }

	        $add = $id ? $translate->trans('movie.flash_messages.update') : $translate->trans('movie.flash_messages.add');
	        $this->addFlash('success', $add);

	        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
	        {
		        return $this->redirectToRoute('app.admin.movie.index');
	        }
	        else
	        {
		        return $this->redirectToRoute('/searchmovie');
	        }
        }


        //envoi du formulaire sous forme de vue

        return $this->render('admin/movie/form.html.twig', [
            'form'=>$form->createView(),
            'movie' => $entity
        ]);
    }

    /**
     * @Route("/admin/movie/delete/{id}", name="app.admin.movie.delete", requirements={"id" = "\d+"})
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