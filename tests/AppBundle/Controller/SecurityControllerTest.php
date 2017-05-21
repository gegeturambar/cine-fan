<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 29/12/16
 * Time: 14:32
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(){
        $client = static::createClient();

        //$crawler = $client->request('GET', '/fr/security/login');
        $crawler = $client->request('GET', '/fr/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("Submit")->form(array("title"=>"aut"));


        $crawler = $client->submit($form);


        dump($crawler);
die();
        /*dump($crawler->filter("form"));die();
        $this->assertCount(0, $crawler->filter("form")->count());
        $this->assertCount(1, $crawler->filter("nav")->count());*/
    }

    /*
    public function testLogout(){
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr/');
        $form = $crawler->selectButton("login");
        $form->form(array("_username"=>"admin","_password"=>"password"));
        $client->submit($form);


    }
    */
}
