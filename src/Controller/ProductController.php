<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    public function createProduct(ValidatorInterface $validator): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$product = new Product();
    	$product->setName(
    		'Marfusha\'Secret');
    	$product->setDefaultShippingAddress('101000');
    	$product->setSellerId(1);
    	
    	$entityManager->persist($product);

    	$entityManager->flush();

    	$errors = $validator->validate($product);
    	if (count($errors) > 0) {
    		return new Response((string) $errors, 400);
    	}

    	return new Response('Saved new product with id '.$product->getId());
    }

    public function showProduct($id)
    {
    	$product = $this->getDoctrine()
    		->getRepository(Product::class)
    		->find($id);

    		if (!$product) {
    			throw $this->createNotFoundException(
    				'No product found for id'.$id
    			);
    		}

    		return $this->render('product/show.html.twig',['product'=>$product]);
    }

    /**
    *@Route("/product/edit{id}")
    */

    public function updateProduct($id)
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$product = $entityManager->getRepository(Product::class)->find($id);

    	if (!$product) {
    		throw $this->createNotFoundException(
    			'Not found for id: '.$id
    		);
    	}

    	$product->setName("New name");
    	$entityManager->flush();

    	return $this->redirectToRoute('product_id',[
    		'id' => $product->getId()
    	]);
    }

    /**
    *@Route("/product/{id}/delete")
    */
    public function deleteProduct($id)
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$product = $entityManager->getRepository(Product::class)->find($id);


    	if (!$product) {
    		throw $this->createNotFoundException(
    			'Not found for id: '.$id
    		);
    	}

    	$entityManager->remove($product);
    	$entityManager->flush();

    	return $this->redirectToRoute('product');    	

    }
}
