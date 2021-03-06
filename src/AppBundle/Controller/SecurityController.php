<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Actor;
use AppBundle\Entity\User;
use AppBundle\Form\ActorType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/security")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="app.security.login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="app.security.logout")
     */
    public function logoutAction(Request $request)
    {

    }

	/**
	 * @Route("/manage", name="app.security.manage")
	 */
	public function indexAction(Request $request)
	{
		$doctrine = $this->getDoctrine();
		// Pour select
		$rc = $doctrine->getRepository("AppBundle:User");
		$records = $rc->findAll();

		// replace this example code with whatever you need
		return $this->render('admin/security/index.html.twig', ['users'=>$records
		]);
	}

    /**
     * @Route("/update/{id}", name="app.security.update",requirements={"id"="\d+"})
     * @Route("/create", name="app.security.create")
     */
    public function formAction(Request $request, $id=null){

        $doctrine = $this->getDoctrine();

        // pour INSERT, DELETE, UPDATE
        $em  = $doctrine->getManager();

        // Pour select
        $rc = $doctrine->getRepository("AppBundle:User");

        // create form
        $entity = is_null($id) ? new User() : $rc->find($id);
        $entityType = UserType::class;

	    $translate  = $this->get('translator');

        $formHandler = $this->get('app.services.userhandler');

        $form = $this->createForm($entityType, $entity);

        // prends en charge la requête
        $form->handleRequest($request);

        if($formHandler->check($form)){

            if($formHandler->process()){

                $message = \Swift_Message::newInstance()
                    ->setTo('9b8f91a58e-b441e5@inbox.mailtrap.io')
                    ->setFrom('send@example.com')
                    ->setSubject('Create User')
                    ->setBody('ffff')
                    ;

                $this->get('mailer')->send($message);

	            $msg        =   $id ? $translate->trans('user.flash_messages.update') : $translate->trans('user.flash_messages.add');
	            $msgType    =  'success';

            }else{
                $msg = is_null($id) ? $translate->trans("user.flash_messages.add") : $translate->trans("user.flash_messages.update");
	            $msgType = 'error';
            }
            $this->addFlash($msgType,ucfirst($msg));
            return $this->redirectToRoute('app.homepage.index');
        }

	    $guest = !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');

		if( !is_null($id) ){
			if($guest)
				throw $this->createAccessDeniedException();
			$title = $translate->trans('user.title_update');
		}elseif ($guest){
			$title = $translate->trans('user.title_create_own');
		}else{
			$title = $translate->trans('user.title_create');
		}


        // envoi du formulaire sous form de html
        return $this->render('admin/security/form.html.twig', array('form'=>$form->createView(),
	        'title' => $title

        ));
    }

	/**
	 * @Route("/delete/{id}", name="app.security.delete", requirements={"id" = "\d+"})
	 */
	public function deleteAction(Request $request, $id)
	{

		$formHandler = $this->get('app.services.userhandler');
		$formHandler->delete($id, 'AppBundle:User' );

        $translate  = $this->get('translator');
        $delete     = $translate->trans('user.flash_messages.delete');
		$this->addFlash('success', $delete);

		return $this->redirectToRoute('app.security.manage');
	}

}