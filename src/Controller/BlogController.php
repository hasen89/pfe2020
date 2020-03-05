<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Produits;
use App\Repository\ArticleRepository;
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
       
     $repo = $this->getDoctrine()->getRepository(Produits::class);
     $produits = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
             'produits'=> $produits ]);
    }
    /**
    * @Route("/",name="home")
    */
    public function home(){
        return $this->render('blog/home.html.twig');
        
    }
    /**
     * @Route("/blog/new",name="blog_create")
     * @Route ("/blog/{id}/edit",name="blog_edit")
     */
    public function form( Produits $produit=null,Request $request, EntityManagerInterface $manager){
 if(!$produit){
  $produit = new Produits();}
    $form = $this->createFormBuilder($produit)
                ->add('title')
                ->add('content')
                ->add('image')
                ->getForm();
                $form->handleRequest($request);
               
                if($form->isSubmitted() && $form->isValid()){
                    if(!$produit->getId()){
               $produit->setCreatedAt(new \DateTime());}

             
               $manager->persist($produit);
               $manager->flush();
               
               return $this->redirectToRoute('blog_show',['id' => $produit->getId()]);


                }
        return $this->render('blog/create.html.twig',[
        'formArticle'=>$form->createView(),
        'editmode'=>$produit->getId()!==null
        
        ]);

     
       
    }
    /**
     * @Route("/blog/{id}",name="blog_show")
     */
    public function show($id){
        $repo = $this->getDoctrine()->getRepository(Produits::class);
        $produit = $repo->find($id);

        return $this->render('blog/show.html.twig',[
            'produit' => $produit ]);
    }
      /**
    * @Route("/contact",name="contact")
    */
    public function conatct(){
        return $this->render('blog/contact.html.twig');
        
    }
  
  }