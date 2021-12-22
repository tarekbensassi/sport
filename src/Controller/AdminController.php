<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Categorie;
use App\Form\AdminType;
use App\Form\CategorieType;
use App\Repository\AdminRepository;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig');

    }
    /**
     * @Route("/utilisateur", name="admin_user", methods={"GET"})
     */
    public function indexuser(): Response
    {
        $User=$this->getDoctrine()->getRepository("App:User")->findAll();
        $Images=$this->getDoctrine()->getRepository("App:Imagesuser")->findAll();
        return $this->render('admin/gereutilisateur.html.twig',array ('User'=> $User, 'images'=> $Images ));

    }
    /**
     * @Route("/annonces", name="admin_annonce", methods={"GET"})
     */
    public function afficheindex(AnnoncesRepository  $AnnoncesRepository): Response
    {

        $annoncee=$this->getDoctrine()->getRepository("App:Annonces")->findAll();
        $Images=$this->getDoctrine()->getRepository("App:Images")->findAll();
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        $user=$this->getDoctrine()->getRepository("App:User")->findAll();
        return $this->render("admin/Annonces.html.twig",array ('categorie'=> $categorie,'user'=> $user,'annoncee'=> $annoncee , 'Images'=> $Images ));
    }
    /**
     * @Route("/detail/{id}", name="detailad", methods={"GET"})
     */
    public function detail(Request $request,$id): Response
    {
        $annonces=$this->getDoctrine()->getRepository("App:Annonces")->findBy([
            'id' => $id,
        ]);
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        return $this->render('admin/detail.html.twig', [
            'annonces' => $annonces,
            'categorie' => $categorie
        ]);

    }
    /**
     * @Route("/ajouteutilisateur", name="ajoute", methods={"GET"})
     */
    public function ajoute(): Response
    {
        return $this->render('admin/ajouteutilisateur.html.twig');

    }
    /**
     * @Route("/categorie", name="categorie", methods={"GET"})
     */
    public function categorie(): Response
    {
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();

        return $this->render('admin/categorie.html.twig',array ('categorie'=> $categorie));


    }
    /**
     * @Route("/ajoutecategorie", name="ajoutecat", methods={"GET","POST"})
     */
    public function ajoutecat(Request $request): Response
    {
        $Categorie = new Categorie();

        //$em= $this->getDoctrine()->getManager();
        // $user = $em->getRepository(User::class)->findBy($id);

        $form = $this->createForm(CategorieType::class, $Categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie');
        }

        return $this->render('admin/ajoutecategorie.html.twig', [
            'categorie' => $Categorie,
            'form' => $form->createView(),
        ]);


    }
    /**
     * @Route("/contact", name="admin_contact", methods={"GET"})
     */
    public function contact(): Response
    {
        $contact=$this->getDoctrine()->getRepository("App:Contact")->findAll();
        return $this->render('admin/Messagerie.html.twig',array ('contact'=> $contact));

    }


}
