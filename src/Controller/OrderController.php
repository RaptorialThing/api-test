<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\Query;


/**
*	@Route("/order")
*/
class OrderController extends AbstractController 
{
	public function index()
	{
		
		return $this->render(
			'order.html.twig',[
				'orders'=>false,
                'object' => false
			]
		);
	}

	public function list()
	{
		$entityManager = $this->getDoctrine()->getManager();
		$orders = $entityManager->getRepository(Order::class)->getRecords();

		return $this->render('order.html.twig',['orders'=>$orders,'object'=>true]);
	}

	public function new(Request $request)
	{
		return $this->render(
			'buyer.html.twig'
		);
	}

    public function deliveryPrice($id)
    {
        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);

            if (!$order) {
                throw $this->createNotFoundException(
                    'No order found for id'.$id
                );
            }

    return $this->render('orders_delivery_price.html.twig',['order'=>$order]);
       
    }

	public function addOrder(ValidatorInterface $validator, Request $request): Response
	{
		$orders = $request->get('order');

    	$entityManager = $this->getDoctrine()->getManager();
    	$order = new Order();
    	$order->setName($orders['order_name']);
    	$order->setShipingAddress($orders['order_shiping_address']);
    	$id = $orders['order_product_id'];

    	$product = $this->getDoctrine()
    		->getRepository(Product::class)
    		->find($id);

    		if (!$product) {
    			throw $this->createNotFoundException(
    				'No product found for id'.$id
    			);
    		}    	

    	$sellerId = $product->getSellerId();	

    	$order->setSellerId($sellerId);
    	$order->setBaseAddress($product->getDefaultShippingAddress());
    	$order->setName($orders['order_name']);
    	$order->setProductId($product->getId());
    	$order->setShipingAddress($orders['order_shiping_address']);
    	
    	$entityManager->persist($order);

    	$entityManager->flush();

    	$errors = $validator->validate($order);
    	if (count($errors) > 0) {
    		return new Response((string) $errors, 400);
    	}

    	$id = $order->getId();
    	$order = $this->getDoctrine()
    		->getRepository(Order::class)
    		->find($id);

    		if (!$order) {
    			throw $this->createNotFoundException(
    				'No order found for id'.$id
    			);
    		}

    return $this->render('order.html.twig',['orders'=>$order]);
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