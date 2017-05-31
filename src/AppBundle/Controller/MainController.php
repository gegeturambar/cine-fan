<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Note;
use AppBundle\Form\MovieSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="app.homepage.index")
     */
    public function indexAction(Request $request)
    {
    	$features = array(
    		"Ajout des utilisateurs",
    		"Ajout des Roles, avec les liens de menus et des foncitonnalités accessibles selon ces derniers",
		    "Ajout d'un workflow permettant aux simples utilisateurs connectés d'entrer des contenus avec un statut published à false tandis que l'administrateur à quand à lui accès au statut",
		    "Ajout du multilangue",
		    "Conversion automatique des prix à l'insertion et au rendu, selon la locale choisi, avec appel à un webService de conversion en ligne",
		    "Traduction automatique des tags en fonction de la locale choisi, avec également un appel à une autre api de traduction en ligne, il aurait été d'utiliser google Translate, mais pour un site de test, je me suis contenté de www.transltr.org",
		    "Ajout des commentaires",
		    "Ajout des Tags",
		    "Ajout du panier",
		    "Génération du pdf lorsque l'on finalise l'achat du parnier",
		    "Commande permettant de supprimer tous les films ( app:movies:delete ) et de la commande discountMovies qui réduit de 10% par défaut le prix des films en base pour simplifier la vie de l'administrateur",
		    "Ajout d'une possibilité de se connecter même avec une base de donnée vide, pour le test, grâce au système de chainage des providers ( avec admin / password pour un administrateur et bob / password pour un simple utilisateur )"
	    );

        // replace this example code with whatever you need
        return $this->render('main/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
	        'features' => $features
        ]);
    }

    /**
     * @Route("/searchmovie", name="app.main.search")
     */
    public function searchmovieAction(Request $request)
    {
        // creation d'un formulaire : sans entité car c'est un formulaire non attaché
        $entityType     = MovieSearchType::class;


        $form = $this->createForm($entityType, null, [
            'action' => $this->generateUrl("app.main.search")
        ]);
        $form->handleRequest($request);//récupération de la saisie


        //envoi du formulaire sous forme de vue

        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository('AppBundle:Movie');/*select*/

        if(count($request->request->get('appbundle_movie_search'))>0)
            $param = $rc->getMovies($request->request->get('appbundle_movie_search'));
        else
            $param = $rc->findAll();

        return $this->render(':main:searchmovie.html.twig', [
            'form'=>$form->createView(),
            'movies' => $param
        ]);
    }

    /**
     * @Route("/searchmoviebycateg/{id}", name="app.main.searchmoviebycateg", requirements={"id" = "\d+"})
     */
    public function searchmoviebycategAction(Request $request)
    {
        $idCateg = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Movie')
            ->createQueryBuilder('movie')
            ->where('movie.category = :categId')->setParameter('categId', $idCateg);

        $query = $queryBuilder->getQuery();

//        print_r($query->getDQL());die();

        $paginator  = $this->get('knp_paginator');

        $blogPosts = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 2)/*limit per page*/
        );

        return $this->render('main/searchmoviebycateg.html.twig', [
            'movies' => $blogPosts,
        ]);
    }

    /**
     * @Route("/list/categ", name="app.main.listcateg")
     */
    public function listcategAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();/*Tout sauf select*/
        $rc = $doctrine->getRepository('AppBundle:Category');/*select*/

		$cats = $rc->findBy(array('published'=>1));

        // replace this example code with whatever you need
        return $this->render('main/searchcateg.html.twig', [
            'categ' => $cats
        ]);
    }

    /**
     * @Route("/movie/detail/{category}/{id}-{slug}", name="main.movie.detail", requirements={"id" = "\d+", "slug" = "\D+", "category" = "\D+"})
    */
    public function detailAction(Request $request,  $id = null, $slug = null)
    {
        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository('AppBundle:Movie');

	    $movie = $rc->find($id);

	    $params = [
	    	'movie' =>  $movie
	    ];

	    if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
		    $rcNote = $doctrine->getRepository('AppBundle:Note');
		    $rcUser = $doctrine->getRepository('AppBundle:User');
		    $user_note = $rcNote->findOneBy(array('user'=>$rcUser->findOneBy(['username'=> $this->getUser()->getUsername() ]),'movie'=>$movie));
		    $params['user_note'] = $user_note;
	    }
        // replace this example code with whatever you need
        return $this->render('main/detailmovie.html.twig', $params);
    }
}
