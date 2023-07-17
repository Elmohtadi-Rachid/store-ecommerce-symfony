<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeContollerController extends AbstractController
{
    private $productRepository;
    private $categoryRepository;
    private $entityManager;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;

    }
    #[Route('/', name: 'home_contoller')]

    public function index(): Response
    {
        $product = $this->productRepository->findAll();
        $categorie=  $this->categoryRepository->findAll();

        return $this->render('home_contoller/index.html.twig', [
            'products' =>$product,
            'categories' =>$categorie,
            //dd($product)
        ]);
    }

    #[Route('/product/{category}', name: 'product_category')]
    public function products_by_category(Category $category): Response 
    {

        $cat = $this->categoryRepository->findAll();
        return $this->render('home_contoller/index.html.twig', [
            'products' =>$category->getProducts(),
            'categories' =>$cat
           
        ]);
    }

    #[Route('/id_product={id}', name: 'show_product')]
    public function tasck_show(Product $product)
    {
       
        //$books = $this->productRepository->find($product);
        return $this->render('home_contoller/detaileOroduct.html.twig', [
            'product' => $product,
        ]);
    }
}
