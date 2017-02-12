<?php

namespace AppBundle\Controller;

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
        // replace this example code with whatever you need
        return $this->render('main/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
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


        // replace this example code with whatever you need
        return $this->render('main/searchcateg.html.twig', [
            'categ' => $rc->findAll()
        ]);
    }

    /**
     * @Route("/movie/detail/{category}/{id}-{slug}", name="main.movie.detail", requirements={"id" = "\d+", "slug" = "\D+", "category" = "\D+"})
    */
    public function detailAction(Request $request,  $id = null, $slug = null)
    {
        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository('AppBundle:Movie');



        // replace this example code with whatever you need
        return $this->render('main/detailmovie.html.twig', [
            'movie' => $rc->find($id)
        ]);
    }
}
