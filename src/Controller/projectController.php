<?php
namespace App\Controller;


use App\Form\Type\TaskType;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;


class projectController extends AbstractController

{


   #[Route('', name: 'project_homepage')]
    public function homepage(): Response {

    return $this->render('project/homepage.html.twig');
    
    }




    #[Route('/project/create', name: 'project-create')] 
    public function create(Request $request, ManagerRegistry $doctrine) : Response {
        $product = new Product();
        $entityManager= $doctrine->getManager();
       
        $form = $this->createForm(TaskType::class, $product);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $task = $form->getData();

            $entityManager->persist($product);
            $entityManager->flush();



            return $this->redirectToRoute('project_success');
        }

     

    return $this->renderForm('project/create.html.twig' , [
        'form' => $form,
        ]);
    }


    
    
        #[Route('/project/success', name: 'project_success')]
        public function success(): Response {

        return $this->render('project/success.html.twig');
    
        }



        #[Route('/project/all', name: 'project_all')]
        public function showAll(ProductRepository $productRepository): Response
        {
        $all = $productRepository->findAll();

        $output = array_map(function ($product) {
            return "<br>id: " . $product->getId() . " name: " . $product->getName();
        }, $all);
        return new Response('<body>All products: '. implode(", ", $output) . '</body>');
        }



        #[Route('/project/{id}', name: 'project_show')]  

        public function show(Product $product): Response
        {
     

            if(!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return new Response('Check out this great product ' . $product->getName());
        }



    }
