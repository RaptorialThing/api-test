<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
*	@Route("/")
*/
class DefaultController extends AbstractController 
{
	public function index()
	{
		
		return $this->render(
			'base.html.twig'
		);
	}
}