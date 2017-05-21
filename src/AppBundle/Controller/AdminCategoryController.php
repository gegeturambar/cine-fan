<?php
/**
 * Created by PhpStorm.
 * User: Joachim
 * Date: 12/02/2017
 * Time: 10:42
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminCategoryController
 * @package AppBundle\Controller
 */
class AdminCategoryController extends Controller
{

	/**
	 * @Route("/admin/category", name="app.admin.category.index")
	 */
	public function indexAction(Request $request)
	{
		$doctrine = $this->getDoctrine();
		// Pour select
		$rc = $doctrine->getRepository("AppBundle:Category");
		$records = $rc->findAll();

		// replace this example code with whatever you need
		return $this->render('admin/category/index.html.twig', ['records'=>$records
		]);
	}


    /**
     * @Route("/admin/category/delete/{id}", name="app.admin.category.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {

        //Service de gestion du formulaire
        $formHandler = $this->get('app.service.handler.formhandler');
        $formHandler->delete($id, 'AppBundle:Category' );

        $translate  = $this->get('translator');
	    $delete     = $translate->trans('category.flash_messages.delete');

        $this->addFlash('success', $delete);

        return $this->redirectToRoute('app.main.listcateg');
    }

    /**
     * @Route("/category/add", name="app.category.form")
     * @Route("/admin/category/add", name="app.admin.category.form")
     * @Route("/admin/category/update/{id}", name="app.admin.category.form.update", requirements={"id" = "\d+"})
     */
    public function formAction(Request $request, $id=null)
    {
        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository('AppBundle:Category');//select

        // creation d'un formulaire : on a l'entité et la class du formulaire... recette de cuisine !
        $entity = $id ? $rc->find($id) : new Category();
        $entityType     = CategoryType::class;



        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);//récupération de la saisie

        //Service de gestion du formulaire
        $formHandler = $this->get('app.service.handler.formhandler');

        if($formHandler->check($form)){
            $formHandler->process();

	        $translate  = $this->get('translator');
	        $add        = $id ? $translate->trans('category.flash_messages.update') : $translate->trans('category.flash_messages.add');
            $this->addFlash('success', $add);
            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            {
	            return $this->redirectToRoute('app.admin.category.index');
            }else{
	            return $this->redirectToRoute('app.homepage.index');
            }
        }

        //envoi du formulaire sous forme de vue
	    return $this->render('admin/category/form.html.twig', [
		    'form' => $form->createView()
	    ]);
    }
}