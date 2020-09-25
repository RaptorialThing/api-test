<?php
namespace App\Service;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class ProductService
{

	private $entityManager;
	protected $container;
    private $validator;
    private $product;
    private $producType;

	public function __construct(ContainerInterface $container, EntityManagerInterface $ientityManager, ValidatorInterface $validator)
	{
		$this->container = $container;
		$this->entityManager = $ientityManager;
        $this->validator = $validator;

	}


	public function getEntityManager()
	{
		return $this->entityManager;
	}
	public function setEntityManager()
	{
		$this->entityManager = $this->container->get('doctrine.orm.entity_manager');
	}

/**
 * @param  int
 * @return Product/Boolean
 */
	public function showProduct($id)
    {
    	return $this->entityManager->getRepository(Product::class)->getProductById($id);
    }

/**
 * @param  int
 * @return Product/Boolean
 */
    public function updateProduct($id)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $product->setName("New name");
        $this->entityManager->flush();  
        return $product; 
    }

    public function newProduct($product)
    {

        $validator = $this->validator;

    	$errors = $validator->validate($product);

    	if (count($errors) > 0) {
    		return false;
    	}

        $this->entityManager->persist($product);
        $this->entityManager->flush();

    	return $product;
    }

/**
 * @param  int
 * @return Product/Boolean
 */
    public function deleteProduct($id)
    {

        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if ($product) {
             $this->entityManager->remove($product);
             $this->entityManager->flush();
        }

        if (!$product) {
            $id = false;
        }
        
        return $id;

    }

}