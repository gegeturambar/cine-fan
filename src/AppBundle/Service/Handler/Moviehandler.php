<?php

namespace AppBundle\Service\Handler;



use AppBundle\Entity\Compteur;
use AppBundle\Service\Utils\UploadUtils;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\FormInterface;

class Moviehandler extends Formhandler
{

    protected $form;
    protected $uploadService;
    protected $email;
    protected $emailAdmin;

    public function __construct(Registry $doctrine, UploadUtils $uploadService, \Swift_Mailer $email, $emailAdmin)
    {
        parent::__construct($doctrine);
        $this->uploadService = $uploadService;
        $this->email = $email;
        $this->emailAdmin = $emailAdmin;
    }

    public function process(){

        //appel du service uploadutils
        if($this->form->getData()->getPicture()!='') {
            $newName = $this->uploadService->uploadFunction($this->form->getData()->getPicture(), 'movie/');

            $this->form->getData()->setPicture($newName);
        }

        parent::process();//fais le process de Formhandler et ensuite s'occupe de l'image

        //ajout du film dans la table de décompte de film : j'avoue ne pas avoir compris pourquoi utiliser une table pour ça...
        //je pourrai tout à fait faire un subscriber qui se lance à chaque requête de page et qui fait un count du nombre
        //de film qu'il y'a dans la base... ce n'est pas si lourd que ça à faire :)
        $this->_nbFilmNow();

        //envoi d'email à l'admin : j'aurai voulu passer par un postflush/postdelete mais je n'ai pas réussi à utiliser ce listener...
        $message = \Swift_Message::newInstance()
            ->setSubject('Nouveau film rentré')
            ->setFrom('joachim_thibout@yahoo.fr')
            ->setTo($this->emailAdmin)
            ->setBody(
                'Un nouveau film a été rentré !',
                'text/html'
            )
        ;
        $this->email->send($message);

    }

    public function delete($id, $entite){


        parent::delete($id, $entite);

        $this->_nbFilmNow();

        $message = \Swift_Message::newInstance()
            ->setSubject('Nouveau film supprimé')
            ->setFrom('joachim_thibout@yahoo.fr')
            ->setTo($this->emailAdmin)
            ->setBody(
                'Un nouveau film a été supprimé !',
                'text/html'
            )
        ;
        $this->email->send($message);

    }

    private function _nbFilmNow(){
        $rc = $this->doctrine->getRepository('AppBundle:Movie');/*select*/
        $nbFilm = $rc->countAllMovies();

        $compteur = new Compteur();
        $em = $this->doctrine->getManager();

        // TODO faire fonctionner
        $client = $em->getRepository('AppBundle:Compteur')->find(1);
        if( !$client){
        	$compteur->setCompteur($nbFilm);
        	$em->persist($compteur);
        }else
        {
	        $client->setCompteur($nbFilm);
	        $em->flush();
        }
    }


}