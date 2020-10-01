<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\Query;
use App\Service\OrderService;
use App\Form\OrderType;

class OrderController extends AbstractController 
{

    private $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function getOrderService() {
        return $this->orderService;
    }

/**
*   @Route("/order")
*/    
	public function index()
	{
		
		return $this->render(
			'order.html.twig',[
				'orders'=>false,
                'object' => false
			]
		);
	}

/** 
 * @Route("/order/list")
 * @return Response
 */
	public function list()
	{
		$entityManager = $this->getDoctrine()->getManager();
		$orders = $entityManager->getRepository(Order::class)->getRecords();

		return $this->render('order.html.twig',['orders'=>$orders,'object'=>true]);
	}
/**
 * @Route("/order/new")
 * @param  Request $request [description]
 * @return [type]           [description]
 */
	public function new(Request $request)
	{
		return $this->render(
			'buyer.html.twig'
		);
	}
/**
 * @Route("/order/delivery/{id}/delivery/price")
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
    public function deliveryPrice($id)
    {
        $order = $this->orderService->showOrder($id);

            if (!$order) {
                throw $this->createNotFoundException(
                    'No order found for id'.$id
                );
            }

    return $this->render('orders_delivery_price.html.twig',['order'=>$order]);
       
    }

/**
 * @Route("/order/add")
 * @param ValidatorInterface $validator [description]
 * @param Request            $request   [description]
 */
	public function addOrder(ValidatorInterface $validator, Request $request): Response
	{

        $order = new Order();

        $form = $this->createForm(OrderType::class,$order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $order = $this->orderService->newOrder($order);

            if (!$order) {
                return new Response('Error saving new order');
            } 

            $route =$this->generateUrl('order_id', ['id' => $order->getId()], 301); 
            return $this->redirect($route);                         
        }

            return $this->render('orderForm.html.twig', [
            'form' => $form->createView()
        ]);          

	}

	public function getOrder($id)
	{

    	$order = $this->getDoctrine()
    		->getRepository(Order::class)
    		->find($id);

    		if (!$order) {
    			throw $this->createNotFoundException(
    				'No order found for id'.$id
    			);
    		}

    return $this->render('order.html.twig',['orders'=>$order,'object'=>false]);

	}
}