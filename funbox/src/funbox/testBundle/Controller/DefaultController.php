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

    public function addAdminAction(Request $request)
    {
    	$CatFood = new CatFood();
        $CatFood->setMode('default');
        $CatFood->setTopping('курочкой');
        $CatFood->setDescription('Hen is awesome!');
        $CatFood->setQuantity(54);
        $CatFood->setFooter('Курочка прекрасна!');

        $addForm = $this->get('form.factory')->createNamedBuilder('addForm', 'form', $CatFood)
            ->add('mode', 'choice', array('label'=>'Состояние','choices'=>array(
                    'default' => 'доступна',
                    'selected' => 'выделена',
                    'disabled' => 'кончилась'
                )))
            ->add('topping', 'text', array('label' => 'Нямушка с'))
            ->add('description', 'textarea', array('label' => 'Её преимущества'))
            ->add('quantity', 'number', array('label' => 'Вес, кг.'))
            ->add('footer', 'textarea', array('label' => 'Подпись внизу'))
            ->add('save', 'submit', array('label' => 'Добавить'))
            ->getForm();
    //submit
        if($request->isMethod('POST')) {
        // add
            $addForm->submit($request->request->get($addForm->getName()));
            if($addForm->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
     
                $em->persist($CatFood);
                $em->flush();
     
                return $this->redirectToRoute('funboxtest_adminPage');
            }
        }
    //render
        return $this->render('funboxtestBundle:Funbox:admin.html.twig', array(
            'type' => 'add',
            'addForm' => $addForm->createView()));
    }

    public function editAdminAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $CatFood = new CatFood();
        $CatFood = $em->getRepository('funboxtestBundle:CatFood')->find($id);

        $editForm = $this->get('form.factory')->createNamedBuilder('editForm', 'form', $CatFood)
            ->add('mode', 'choice', array('label'=>'Состояние','choices'=>array(
                    'default' => 'доступна',
                    'selected' => 'выделена',
                    'disabled' => 'кончилась'
                )))
           ->add('topping', 'text', array('label' => 'Нямушка с'))
            ->add('description', 'textarea', array('label' => 'Её преимущества'))
            ->add('quantity', 'number', array('label' => 'Вес, кг.'))
            ->add('footer', 'textarea', array('label' => 'Подпись внизу'))
            ->add('save', 'submit', array('label' => 'Изменить'))
            ->getForm();

    //submit
        if($request->isMethod('POST')) {

            $editForm->submit($request->request->get($editForm->getName()));

            if($editForm->isValid()){
                $data = $request->request->get($editForm->getName());
                if(!is_int($data['quantity']))
                    return new Response("'Количество' не похоже на число.");
                $em = $this->getDoctrine()->getEntityManager();
                $CatFood->setMode($data['mode']);
                $CatFood->setTopping($data['topping']);
                $CatFood->setDescription($data['description']);
                $CatFood->setQuantity($data['quantity']);
                $CatFood->setFooter($data['footer']);
     
                $em->persist($CatFood);
                $em->flush();
     
                return $this->redirectToRoute('funboxtest_adminPage');
            }
        }
    //render
        return $this->render('funboxtestBundle:Funbox:admin.html.twig', array(
            'type' => 'edit',
            'editForm' => $editForm->createView()));
    }

    public function deleteAdminAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $CatFood = $em->getRepository('funboxtestBundle:CatFood')->find($id);
        $em->remove($CatFood);
        $em->flush();

        return $this->redirectToRoute('funboxtest_adminPage');
    }

    public function showAdminAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $editForm = $em->getRepository('funboxtestBundle:CatFood')->findAll();
        foreach ($editForm as $product) {
            $editFormProducts[] = array(
                    'id' => $product->getId(),
                    'mode' => $product->getMode(),
                    'topping' => $product->getTopping(),
                    'description' => $product->getDescription(),
                    'quantity' => $product->getQuantity(),
                    'footer' => $product->getFooter()
                );
        }

    //render
        return $this->render('funboxtestBundle:Funbox:admin.html.twig', array(
            'type' => 'show',
            'editFormProducts' => $editFormProducts
            ));        
    }
}
