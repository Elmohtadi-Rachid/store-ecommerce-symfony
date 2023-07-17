<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrederController extends AbstractController
{
    private $orderRepository;
    private $entityManager;

    public function __construct(OrderRepository $orderRepository, ManagerRegistry $doctrine)
    {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $doctrine->getManager();

    }
    #[Route('/oreder', name: 'app_oreder')]
    public function index(): Response
    {
        return $this->render('oreder/index.html.twig', [
            'controller_name' => 'OrederController',
        ]);
    }

    #[Route('/oreders', name: 'user_order_list')]
    public function UserOrders(): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user=$this->getUser();
         return $this->render('oreder/index.html.twig', [
            'usee' => $user,
            
        ]);
    }

    #[Route('/store/order{product}', name: 'order_store')]
    public function store(Product $product): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $Order = new Order();
        $Order->setPname($product->getName());
        $Order->setPrice($product->getPrice());
        $Order->setStatus('processing...');
        $Order->setUser($this->getUser());
      
            $this->entityManager->persist($Order);
            $this->entityManager->flush();
            $this->addFlash('success','your order was saved');
           
            return $this->redirectToRoute('user_order_list');
      
       
}
}