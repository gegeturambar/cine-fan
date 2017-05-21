<?php
/**
 * Created by PhpStorm.
 * User: Joachim
 * Date: 12/02/2017
 * Time: 10:42
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminTagController
 * @package AppBundle\Controller
 */
class AdminTagController extends Controller
{

	/**
	 * @Route("/admin/tag", name="app.admin.tag.index")
	 */
	public function indexAction(Request $request)
	{
		$doctrine = $this->getDoctrine();
		// Pour select
		$rc = $doctrine->getRepository("AppBundle:Tag");
		$records = $rc->findAll();

		// replace this example code with whatever you need
		return $this->render('admin/tag/index.html.twig', ['records'=>$records
		]);
	}


    /**
     * @Route("/admin/tag/delete/{id}", name="app.admin.tag.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {

        //Service de gestion du formulaire
        $formHandler = $this->get('app.service.handler.formhandler');
        $formHandler->delete($id, 'AppBundle:Tag' );

        $translate  = $this->get('translator');
        $delete     = $translate->trans('tag.flash_messages.delete');

        $this->addFlash('success', $delete);

        return $this->redirectToRoute('app.main.listcateg');
    }

    /**
     * @Route("/tag/add", name="app.tag.form")
     * @Route("/admin/tag/add/", name="app.admin.tag.form")
     * @Route("/admin/tag/update/{id}", name="app.admin.tag.form.update", requirements={"id" = "\d+"})
     */
    public function formAction(Request $request, $id=null)
    {
        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository('AppBundle:Tag');//select

        // creation d'un formulaire : on a l'entité et la class du formulaire... recette de cuisine !
        $entity = $id ? $rc->find($id) : new Tag();
        $entityType     = TagType::class;


        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);//récupération de la saisie

        //Service de gestion du formulaire
        $formHandler = $this->get('app.service.handler.formhandler');

        if($formHandler->check($form)){
            $formHandler->process();

	        $translate  = $this->get('translator');
	        $add        = $id ? $translate->trans('tag.flash_messages.update') : $translate->trans('tag.flash_messages.add');
            $this->addFlash('success', $add);
            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            {
	            return $this->redirectToRoute('app.admin.tag.index');
            }else{
	            return $this->redirectToRoute('app.homepage.index');
            }
        }

        //envoi du formulaire sous forme de vue
	    return $this->render('admin/tag/form.html.twig', [
		    'form' => $form->createView()
	    ]);
    }
}