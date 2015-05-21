<?php

namespace funbox\testBundle\Controller;

use testBundle\Entity\CatFood
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('funboxtestBundle:Default:index.html.twig');
    }
    public function helloAction($name)
    {
        return $this->render('funboxtestBundle:Default:hello.html.twig', array('name' => $name));
    }

    public function createAction()
    {
    	$CatFood = new CatFood();
    	$CatFood->setMode('default');
    	$CatFood->setTopping('курочкой');
    	$CatFood->setDesc('Hen is awesome!');
    	$CatFood->setQuantity(54);
    	$CatFood->setFooter('Курочка прекрасна!');

    	$em = $this->getDoctrine
    }
}
