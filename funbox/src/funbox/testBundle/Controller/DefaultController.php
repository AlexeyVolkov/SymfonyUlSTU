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

    public function showAdminAction(Request $request)
    {
        $CatFood = new CatFood();
        $CatFood->setMode('default');
        $CatFood->setTopping('курочкой');
        $CatFood->setDescription('Hen is awesome!');
        $CatFood->setQuantity(54);
        $CatFood->setFooter('Курочка прекрасна!');

        $addForm = $this->get('form.factory')->createNamedBuilder('addForm', 'form', $CatFood)
            ->add('mode', 'text')
            ->add('topping', 'text', array('label' => 'с'))
            ->add('description', 'textarea')
            ->add('quantity', 'number')
            ->add('footer', 'textarea')
            ->add('save', 'submit', array('label' => 'Добавить'))
            ->getForm();


    // remove
        $em = $this->getDoctrine()->getEntityManager();
        $CatFood = $em->getRepository('funboxtestBundle:CatFood')->findAll();
        foreach ($CatFood as $product) {
            $idList[$product->getId()] = $product->getId();
        }
        
        $removeForm = $this->get('form.factory')->createNamedBuilder('removeForm', 'form', $CatFood)
            ->add('id', 'choice', array('label'=>'Нямушку №','choices' => $idList))
            ->add('save', 'submit', array('label' => 'Удалить'))
            ->getForm();

    // edit
        /*$em = $this->getDoctrine()->getEntityManager();
        $CatFood = $em->getRepository('funboxtestBundle:CatFood')->findAll();
        foreach ($CatFood as $product) {
            $idList[$product->getId()] = $product->getId();
        }
        
        $editForm = $this->get('form.factory')->createNamedBuilder('editForm', 'form', $CatFood)
            ->add('id', 'choice', array('label'=>'Нямушку №','choices' => $idList))
            ->add('save', 'submit', array('label' => 'Редактировать'))
            ->getForm();*/
        $em = $this->getDoctrine()->getEntityManager();
        $editForm = $em->getRepository('funboxtestBundle:CatFood')->findAll();
        foreach ($editForm as $product) {
            $editFormProducts[] = array(
                    'id' => $product->getId(),
                    'mode' => $product->getMode(),
                    'topping' => $product->getTopping(),
                    'quantity' => $product->getQuantity()
                );
        }

    // submit
        if($request->isMethod('POST')) {
        // add
            $addForm->submit($request->request->get($addForm->getName()));
            if($addForm->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
     
                $em->persist($CatFood);
                $em->flush();
     
                return new Response('Created product id '.$CatFood->getId());
            }
        // remove
            $removeForm->submit($request->request->get($removeForm->getName()));
            if($removeForm->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
                $id = $request->request->get($removeForm->getName())['id'];
     
                $CatFood = $em->getRepository('funboxtestBundle:CatFood')->find($id);
                $em->remove($CatFood);
                $em->flush();
     
                return new Response('Product #'.$id.' has been deleted');
            }
        // edit
            $editForm->submit($request->request->get($editForm->getName()));
            if($editForm->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
                $id = $request->request->get($editForm->getName())['id'];
     
                $CatFood = $em->getRepository('funboxtestBundle:CatFood')->find($id);
                $em->remove($CatFood);
                $em->flush();
     
                return new Response('Product #'.$id.' has been deleted');
            }
        }

    //render
        return $this->render('funboxtestBundle:Funbox:admin.html.twig', array(
            'addForm' => $addForm->createView(),
            'removeForm' => $removeForm->createView(),
            'editFormProducts' => $editFormProducts
            ));
        
    }

    public function editAdmin()
    {
    	return $this->render('funboxtestBundle:Default:editAdmin.html.twig');
    }
}
