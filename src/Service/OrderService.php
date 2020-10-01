<?php 

namespace App\Service;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product;

class OrderService
{
	private $entityManager;
	protected $container;
	private $validator;
	private $order;
	private $orderType;

	public function __construct(ContainerInterface $container, EntityManagerInterface $ientityManager, ValidatorInterface $validator) {
		$this->container = $container;
		$this->entityManager = $ientityManager;
		$this->validator = $validator;
	}

	public function getEntityManager() {
		return $this->entityManager;
	}

	public function setEntityManager() {
		$this->entityManager = $this->container->get('doctrine.orm.entity_manager');
	}
/**
 * 
 * @param  [int] $id [description]
 * @return [Order::class]     [description]
 */
	public function showOrder($id) {
		return $this->entityManager->getRepository(Order::class)->getOrderById($id);
	}

	public function updateOrder($id) {
		$order = $this->entityManager->getRepository(Order::class)->find($id);
		$order->setName("New name");
		$this->entityManager->flush();
		return $order;
	}

   public function newOrder($order)
    {

    	$product = $this->entityManager
    		->getRepository(Product::class)
    		->find($order->getProductId());

    		if (!$product) {
    			return false;
    		}    	
    	
    	$this->entityManager->persist($order);

    	$this->entityManager->flush();

    	$errors = $this->validator->validate($order);
    	if (count($errors) > 0) {
    		return false;
    	}

    	return $order;
    }

/**
 * @param  int
 * @return Order/Boolean
 */
    public function deleteOrder($id)
    {

        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if ($order) {
             $this->entityManager->remove($order);
             $this->entityManager->flush();
        }

        if (!$order) {
            $id = false;
        }
        
        return $id;

    }	
}