<?php 

namespace App\Product;

use App\Service\ProductService;

class ProductManagerStaticFactory
{
	public static function createProductManagerFactory()
	{
		$productManager = new ProductService();

		return $productManager;
	}
}