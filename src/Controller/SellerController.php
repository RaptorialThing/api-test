<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
*	@Route("/seller")
*/
class SellerController extends AbstractController 
{
	public function index()
	{
		return $this->render(
			'seller.html.twig'
		);
	}
}