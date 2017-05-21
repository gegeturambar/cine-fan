<?php

namespace AppBundle\Event;
use Symfony\Component\EventDispatcher\Event;

/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 28/12/16
 * Time: 16:46
 */
class OrderFinalisationEvent extends Event
{
    /**
     * @return mixed
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param mixed $basket
     */
    public function setBasket($basket)
    {
        $this->basket = $basket;
    }

    /**
     * @return mixed
     */
    public function getPdfName()
    {
        return $this->pdf_name;
    }

    /**
     * @param mixed $pdf_name
     */
    public function setPdfName($pdf_name)
    {
        $this->pdf_name = $pdf_name;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * @param mixed $user_email
     */
    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }
    public function __construct(){
    }

    private $basket;
    private $user_email;
    private $user_id;
    private $pdf_name;
}

