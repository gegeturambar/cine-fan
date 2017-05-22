<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/12/16
 * Time: 09:53
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Commentaire;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/commentaire")
 */
class CommentaireController extends Controller
{

    /**
     * @Route("/commentaire/add", name="app.commentaire.add")
     */
    public function addAction(Request $request ){

    	if($request->isXmlHttpRequest())
	    {
		    $logger = $this->get('logger');
		    $json =   $request->getContent();

		    $data = json_decode($json);

	        $comment = $data->commentaire;
		    $movie_id = $data->movie;

		    $logger->info("comment => ".$comment);

		    $doctrine = $this->getDoctrine();
		    // Pour select
		    $rc = $doctrine->getRepository("AppBundle:Movie");

		    $logger->info('movie_id  => '.$movie_id);

		    $movie = $rc->find($movie_id);

		    $entity = new Commentaire();
		    $entity->setCommentaire($comment);
		    $entity->setPublished(true);
		    $entity->setMovie($movie);
		    $entity->setUser($this->getUser());

		    $em = $doctrine->getManager();
		    $success = true;

		    try
		    {
			    $em->persist($entity);
			    $em->flush();
		    }catch (Exception $ex){
			    $success = false;
		    }
		    $ret = array('success'=>$success,'commentaire'=> $entity->getCommentaire(), 'user'=>$entity->getUser()->getUsername());
		    return new JsonResponse($ret);
	    }

        return new JsonResponse(array('success'=>true)
        );
        //return $this->render('movie/index.html.twig', array('mov'=>$record)) ;
    }
}