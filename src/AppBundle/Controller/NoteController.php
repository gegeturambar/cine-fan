<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/12/16
 * Time: 09:53
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Note;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\User;

/**
 * @Route("/note")
 */
class NoteController extends Controller
{

    /**
     * @Route("/note/add", name="app.note.add")
     */
    public function addAction(Request $request ){

    	if($request->isXmlHttpRequest())
	    {
		    $logger = $this->get('logger');
		    $json =   $request->getContent();

		    $data = json_decode($json);

	        $rate = $data->rate;


		    $movie_id = $data->movie;

		    $logger->info("rate => ".$rate);

		    $doctrine = $this->getDoctrine();

		    /** @var  $user User */
		    $user = $this->getUser();
		    $rc = $doctrine->getRepository("AppBundle:User");

		    /** @var $user \AppBundle\Entity\User */
		    $user = $rc->findOneBy(['username' => $user->getUsername() ]);

		    // Pour select
		    $rc = $doctrine->getRepository("AppBundle:Movie");

		    $logger->info('movie_id  => '.$movie_id);

		    $movie = $rc->find($movie_id);
			if(!$movie)
				return new JsonResponse(array('success'=>false));


		    /** @var $note Note*/
		    $note = new Note();
		    $note->setValue($rate);
		    $note->setMovie($movie);
		    $note->setUser($user);

		    $em = $doctrine->getManager();
		    $success = true;

		    try
		    {
			    $em->persist($note);
			    $em->flush();
		    }catch (Exception $ex){
			    $success = false;
		    }

		    $ret = array('success'=>$success,'note'=> $note->getValue(), 'global_note'=>$note->getMovie()->getGlobalNote());
		    return new JsonResponse($ret);
	    }

        return new JsonResponse(array('success'=>true)
        );

        //return $this->render('movie/index.html.twig', array('mov'=>$record)) ;
    }
}