<?php
/**
 * Created by PhpStorm.
 * User: wamobi5
 * Date: 19/12/16
 * Time: 12:50
 */

namespace AppBundle\Service\Twig;


use Doctrine\Bundle\DoctrineBundle\Registry;

class AllMoviesService
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function allMovies(){
        $rc = $this->doctrine->getRepository('AppBundle:Movie');/*select*/
        return $nbFilm = $rc->countAllMovies();
    }

}