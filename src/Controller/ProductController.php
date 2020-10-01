<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ProductService;
use App\Repository\ProductRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProducType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ProductController extends AbstractController
{

    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;

    }

    public function getProductService()
    {
        return $this->productService;
    }

    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
    * @Route("/create/product", name="product_create")
    */

    public function createProduct(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProducType::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product = $this->productService->newProduct($product);

            if (!$product) {
                return new Response('Error saving new product ');
            } 

            $route =$this->generateUrl('product_id', ['id' => $product->getId()], 301); 
            return $this->redirect($route);                         
        }

            return $this->render('buyerForm.html.twig', [
            'form' => $form->createView()
        ]);          

    }

    // public function showProduct($id)
    // {
    // 	$product = $this->getDoctrine()
    // 		->getRepository(Product::class)
    // 		->find($id);

    // 		if (!$product) {
    // 			throw $this->createNotFoundException(
    // 				'No product found for id'.$id
    // 			);
    // 		}

    // 		return $this->render('product/show.html.twig',['product'=>$product]);
    // }

    /**
    *@Route("/product/{id}", name="product_id")
    */

    public function showProduct($id)
    {
        $product = $this->productService->showProduct($id);

        if (!$product) {
         throw $this->createNotFoundException(
             'Not found for id: '.$id
         );
        }            

        return $this->render('product/show.html.twig',['product'=>$product]);
    }


    /**
    *@Route("/product/update/{id}")
    */

    public function updateProduct(Request $request, $id)
    {
        $product = new Product();

        $form = $this->createForm(ProducType::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product = $this->productService->updateProduct($id,$product);

            if (!$product) {
                return new Response('Error updating product ');
            } 

            $route =$this->generateUrl('product_id', ['id' => $product->getId()], 301); 
            return $this->redirect($route);                         
        }

            return $this->render('buyerForm.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
    *@Route("/product/delete/{id}")
    */
    public function deleteProduct($id)
    {

        $id = $this->productService->deleteProduct($id);

        if (!$id) {
            throw $this->createNotFoundException(
                'Not found for id: '.$id
            );
        }        

    	return new Response('Deleted product with id '.$id);

    }
}
