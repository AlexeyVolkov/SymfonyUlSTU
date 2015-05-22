<?php

namespace funbox\testBundle\Controller;

use funbox\testBundle\Entity\CatFood;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    	$CatFood->setDescription('Hen is awesome!');
    	$CatFood->setQuantity(54);
    	$CatFood->setFooter('Курочка прекрасна!');

    	$em = $this->getDoctrine()->getManager();

    	$em->persist($CatFood);
    	$em->flush();

    	return new Response('Created product id '.$CatFood->getId());
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $CatFood = $em->getRepository('funboxtestBundle:CatFood')->find($id);
        $em->remove($CatFood);
        $em->flush();

        return new Response('CatFood id '.$id.' deleted');
    }

    public function showAllAction()
    {
    	$CatFood = $this->getDoctrine()
    		->getRepository('funboxtestBundle:CatFood')
    		->findAll();

    	return $this->render('funboxtestBundle:Funbox:index.html.twig', array('CatFood' => $CatFood));

    	if(!$CatFood){
    		throw $this->createNotFoundException(
    			'No CatFood found'
    			);
    	}
    }

    public function showAdminAction()
    {
        $CatFood = new CatFood();
        $CatFood->setMode('default');
        $CatFood->setTopping('курочкой');
        $CatFood->setDescription('Hen is awesome!');
        $CatFood->setQuantity(54);
        $CatFood->setFooter('Курочка прекрасна!');

        $form = $this->createFormBuilder($CatFood)
            ->add('mode', 'text')
            ->add('topping', 'text')
            ->add('description', 'text')
            ->add('quantity', 'integer')
            ->add('footer', 'text')
            ->add('save', 'submit', array('label' => 'Add Cat Food'))
            ->getForm();

    	return $this->render('funboxtestBundle:Funbox:admin.html.twig', array('form' => $form->createView(),));
    }

    public function editAdmin()
    {
    	return $this->render('funboxtestBundle:Default:editAdmin.html.twig');
    }
}
