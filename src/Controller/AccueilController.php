<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categorie;
use App\Entity\Contact;
use App\Entity\Images;
use App\Form\AnnoncesType;
use App\Form\CategorieType;
use App\Form\ContactType;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {

        $annonces=$this->getDoctrine()->getRepository("App:Annonces")->findAll();
        $Images=$this->getDoctrine()->getRepository("App:Images")->findAll();
        $user=$this->getDoctrine()->getRepository("App:User")->findAll();
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        return $this->render("sportindiv/index.html.twig",array (
            'categorie'=> $categorie ,
            'user'=> $user ,
            'annonces'=> $annonces ,
            'Images'=> $Images ));
        //return $this->render('sportindiv/index.html.twig');
    }
    /**
     * @Route("/apropos", name="apropos", methods={"GET"})
     */
    public function apropos(Request $request): Response
    {
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        return $this->render('sportindiv/about.html.twig' ,array (
            'categorie'=> $categorie ,

        ));
    }
    /**
     * @Route("/ex", name="ex", methods={"GET"})
     */
    public function ex(Request $request): Response
    {

        return $this->render('sportindiv/ex.html.twig');
    }
    /**
     * @Route("/annonce/{id}", name="annonce", methods={"GET"})
     */
    public function annonce(Request $request,$id): Response
    {

        $annonces=$this->getDoctrine()->getRepository("App:Annonces")->findBy([
                'categorie' => $id,
            ]);
        $Images=$this->getDoctrine()->getRepository("App:Images")->findAll();
        $user=$this->getDoctrine()->getRepository("App:User")->findAll();
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        return $this->render("sportindiv/annonce.html.twig" ,array (
            'categorie'=> $categorie ,
            'user'=> $user ,
            'annonces'=> $annonces ,
            'Images'=> $Images
        ));
        //return $this->render('sportindiv/index.html.twig');
    }

    /**
     * @Route("/details/{id}", name="details", methods={"GET"})
     */
    public function details(Request $request,$id): Response
    {
        $annonces=$this->getDoctrine()->getRepository("App:Annonces")->findBy([
            'id' => $id,
        ]);
        $Images=$this->getDoctrine()->getRepository("App:Images")->findAll();
        $user=$this->getDoctrine()->getRepository("App:User")->findAll();
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        return $this->render('sportindiv/detail.html.twig', [
            'annonces' => $annonces,
            'categorie' => $categorie
        ]);

    }

    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     */
    public function contact(Request $request): Response
    {

        $Contact = new Contact();

        $form = $this->createForm(ContactType::class, $Contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Contact);
            $entityManager->flush();

            return $this->redirectToRoute('contact');
        }
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        return $this->render('sportindiv/contact.html.twig', [
            'Contact' => $Contact,
            'categorie'=>$categorie,
            'form' => $form->createView(),
        ]);

    }



}
