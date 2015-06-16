<?php

namespace funbox\testBundle\Controller;

use funbox\testBundle\Entity\CatFood;
use funbox\testBundle\Entity\Misc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $Misc = $this->getDoctrine()
            ->getRepository('funboxtestBundle:Misc')
            ->find(1);

        $defaultData = array('message' => 'Type your message here');
        $sortForm = $this->get('form.factory')->createNamedBuilder('sortForm', 'form', $defaultData)
            ->add('topping', 'text', array('required' => false))
            ->add('quantity', 'choice', array('choices'=>array(
                    'more' => 'Больше',
                    'less' => 'Меньше'
                )))
            ->add('quantityInput', 'number',array('required' => false))
            ->add('sort', 'submit', array('label' => 'Фильтровать'))
            ->getForm();
        //submit
        if($request->isMethod('POST')) {
        // sort
            $sortForm->submit($request->request->get($sortForm->getName()));

            if($sortForm->isValid()){

                $comparison['more'] = '>';
                $comparison['less'] = '<';

                $data = $request->request->get($sortForm->getName());
                $repo = $this->getDoctrine()->getRepository('funboxtestBundle:CatFood');
            //create vars
                $queryWhere = 'CatFood.mode = :default';
                $queryParamets = array('default' => 'default');
                if($data['topping'] != null){
                    $queryWhere .= ' AND CatFood.topping LIKE :topping';
                    $queryParamets['topping'] = '%'.$data['topping'].'%';
                }
                if($data['quantityInput'] != null){
                    $queryWhere .= ' AND CatFood.quantity '.$comparison[$data['quantity']].' :quantityInput';
                    $queryParamets['quantityInput'] = $data['quantityInput'];
                }
            //send query
                $query = $repo->createQueryBuilder('CatFood')
                    ->where($queryWhere)
                    ->setParameters($queryParamets)
                    ->orderBy('CatFood.quantity', 'DESC')
                    ->getQuery();

                $CatFood = $query->getResult();
            }
        }
        else{
            $CatFood = $this->getDoctrine()
                ->getRepository('funboxtestBundle:CatFood')
                ->findAll();
        }



        return $this->render('funboxtestBundle:Funbox:index.html.twig', array(
            'CatFood' => $CatFood, 
            'Misc' => $Misc, 
            'sortForm' => $sortForm->createView()));

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
    public function miscAdminAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $Misc = new Misc();
        $Misc = $em->getRepository('funboxtestBundle:Misc')->find(1);
        $miscForm = $this->get('form.factory')->createNamedBuilder('miscForm', 'form', $Misc)
           ->add('h1', 'text', array('label' => 'Заголовок'))
            ->add('footer', 'textarea', array('label' => 'Код счётчика'))
            ->add('save', 'submit', array('label' => 'Изменить'))
            ->getForm();

    //submit
        if($request->isMethod('POST')) {
            $miscForm->submit($request->request->get($miscForm->getName()));

            if($miscForm->isValid()){
                $data = $request->request->get($miscForm->getName());
                $em = $this->getDoctrine()->getEntityManager();
                $Misc->setH1($data['h1']);
                $Misc->setFooter($data['footer']);
     
                $em->persist($Misc);
                $em->flush();
     
                return $this->redirectToRoute('funboxtest_adminPage');
            }
        }
    //render
        return $this->render('funboxtestBundle:Funbox:admin.html.twig', array(
            'type' => 'misc',
            'miscForm' => $miscForm->createView()));
    }

    public function searchResultsAction(Request $request){
        $fm = $this->get('padam87_search.filter.manager');
        $data = array(
            'topping' => 'рыб*'
        );
        $filter = new Filter($data, 'funboxtestBundle:CatFood', 'alias');
        $qb = $fm->createQueryBuilder($filter);
    }
}
