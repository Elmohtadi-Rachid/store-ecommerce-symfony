<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductContollerController extends AbstractController
{
    private $productRepository;
    private $entityManager;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;

    }
    #[Route('/product/leste', name: 'product_leste')]
    public function index(): Response
    {
        $product = $this->productRepository->findAll();
        return $this->render('product_contoller/liste_product.html.twig', [
            'products' =>$product,
            //dd($product)
        ]);
    }

    #[Route('/store/product', name: 'Ajouter_product')]
    public function store(Request $request): Response
    {
        $Product = new Product();
        $form = $this->createForm(ProductType::class,$Product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $Product = $form->getData();
            if($request->files->get('product')['image'])
            {
                $image = $request->files->get('product')['image'];
                $image_name = time().'_'.$image->getClientOriginalName();
                $image->move(
                    $this->getParameter('image_producte'),$image_name
                );
                $Product->setImage($image_name);
            }
            $this->entityManager->persist($Product);
            $this->entityManager->flush();
            $this->addFlash('success','your product was saved');
           
            return $this->redirectToRoute('product_leste');
        }
        return $this->render('product_contoller/create.html.twig', [
            'form' => $form
              
        ]);
    }

    #[Route('/store/delete/{id}', name: 'delete_product')]
    public function delete_product(Product $product)
    {
        $filsystem = new Filesystem();
        $image_path = './uplodes'.$product->getImage();
        if($filsystem->exists($image_path)){
            $filsystem->remove($image_path);
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        $this->addFlash('success','your product was deleted');
        return $this->redirectToRoute('product_leste');
    }

    #[Route('/store/product/edite/{id}', name: 'edite_product')]
    public function edite(Request $request ,Product $product): Response
    {
        
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();
            if($request->files->get('product')['image'])
            {
                $image = $request->files->get('product')['image'];
                $image_name = time().'_'.$image->getClientOriginalName();
                $image->move(
                    $this->getParameter('image_producte'),$image_name
                );
                $product->setImage($image_name);
            }
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $this->addFlash('success','your product was edite');
          
           
            return $this->redirectToRoute('product_leste');
        }
        return $this->render('product_contoller/edite.htm.twig', [
            'form' => $form
              
        ]);
    }

    #[Route('/product/{id}', name: 'ttdetaile_product')]
    public function detaile_Product(Product $product): Response 
    {
        //$product = $this->productRepository->findAll();
        return $this->render('home_contoller/detaileOroduct.html.twig', [
            'products' =>$product

           
        ]);
    }
}
