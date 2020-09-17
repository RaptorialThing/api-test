<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
*	@Route("/buyer")
*/
class BuyerController extends AbstractController 
{
	public function index()
	{
		return $this->render(
			'buyer.html.twig'
		);
	}
}