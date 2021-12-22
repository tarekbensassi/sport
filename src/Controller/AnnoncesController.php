<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categorie;
use App\Entity\Images;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user")
 */
class AnnoncesController extends AbstractController
{

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {

        return $this->render('user/index.html.twig');
    }
    /**
     * @Route("/annonces", name="annonces", methods={"GET"})
     */
    public function afficheindex(AnnoncesRepository  $AnnoncesRepository): Response
    {

        $annoncee=$this->getDoctrine()->getRepository("App:Annonces")->findAll();
        $Images=$this->getDoctrine()->getRepository("App:Images")->findAll();
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        $user=$this->getDoctrine()->getRepository("App:User")->findAll();
        return $this->render("user/Annonces.html.twig",array ('categorie'=> $categorie,'user'=> $user,'annoncee'=> $annoncee , 'Images'=> $Images ));
    }

    /**
     * @Route("/ajouteannonce", name="annonces_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $annonces = new Annonces();
        $em = $this->getDoctrine()->getManager();
        $annonces->setUser($this->getUser());
        $id= (int)$request->get('select-name');
        $idc =$em->getRepository(Categorie::class)->find($id);
        //$float = (int)$num;
        dump($idc);

        $annonces->setCategorie($idc);

      // $annonces->setCategorie($this->getCategorie());


        //$em= $this->getDoctrine()->getManager();
       // $user = $em->getRepository(User::class)->findBy($id);

        $form = $this->createForm(AnnoncesType::class, $annonces);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $annonces->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($annonces);
            $entityManager->flush();

            return $this->redirectToRoute('annonces');
        }
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        return $this->render('user/ajouteannonces.html.twig', [
            'annonces' => $annonces,
            'categorie'=>$categorie,
            'id'=>'$id',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_show", methods={"GET"})
     */
    public function show(Annonces $annonce): Response
    {
        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
        ]);

    }
    /**
     * @Route("/detail/{id}", name="detail", methods={"GET"})
     */
    public function detail(Request $request,$id): Response
    {
        $annonces=$this->getDoctrine()->getRepository("App:Annonces")->findBy([
            'id' => $id,
        ]);
        $categorie=$this->getDoctrine()->getRepository("App:Categorie")->findAll();
        return $this->render('user/detail.html.twig', [
            'annonces' => $annonces,
            'categorie' => $categorie
        ]);

    }


    /**
     * @Route("/{id}/edit", name="annonces_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonces $annonce): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_delete", methods={"POST"})
     */
    public function delete(Request $request, Annonces $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonces_index', [], Response::HTTP_SEE_OTHER);
    }


}
