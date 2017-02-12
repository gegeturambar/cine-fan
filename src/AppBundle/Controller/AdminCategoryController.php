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

class AdminCategoryController extends Controller
{
    /**
     * @Route("/delete/{id}", name="app.admin.category.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {

        //Service de gestion du formulaire
        $formHandler = $this->get('app.service.handler.formhandler');
        $formHandler->delete($id, 'AppBundle:Category' );

//        $translate  = $this->get('translator');
//        $delete     = $translate->trans('form.category.message.delete');

        $delete     = 'La catégorie a bien été supprimée';

        $this->addFlash('success', $delete);

        return $this->redirectToRoute('app.main.listcateg');
    }

    /**
     * @Route("/add/category", name="app.admin.category.form")
     * @Route("/update/category/{id}", name="app.admin.category.form.update", requirements={"id" = "\d+"})
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

//            $translate  = $this->get('translator'); // pas besoin ici... mais je sais ce qu'il faut appeler !
//            $add        = $id ? $translate->trans('form.category.message.update') : $translate->trans('form.category.message.ajout');

            $add        = $id ? 'La catégorie a été mise à jour' : 'La catégorie a été ajoutée';
            $this->addFlash('success', $add);
            return $this->redirectToRoute('app.main.listcateg');
        }

        //envoi du formulaire sous forme de vue
        return $this->render('admin-category/form.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}