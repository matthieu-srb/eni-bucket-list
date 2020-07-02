<?php

namespace App\Controller;

use App\Entity\Idea;
use App\Form\IdeaType;
use App\Repository\IdeaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class IdeaController extends Controller
{
    /**
     * @Route("/ideas", name="idea_list")
     */
    public function list()
    {
        //récupère le repository pour les Idea, afin de faire une requête SELECT
        $repo = $this->getDoctrine()->getRepository(Idea::class);
        //fait la requête SELECT avec un WHERE et un ORDER BY et une LIMIT à 50
        $ideas = $repo->findIdeasWithCategories();

        return $this->render('idea/list.html.twig', [
            //passe notre variable à twig
            "ideas" => $ideas,
        ]);
    }

    /**
     * ici j'utilisie l'injection de dépendance pour récupérer mon repo (juste pour le plaisir)
     * @Route("/ideas/detail/{id}", name="idea_detail")
     */
    public function detail($id, IdeaRepository $repo)
    {
        //récupère une idée en fonction de sa clé primaire
        $idea = $repo->find($id);
        $pif = "paf";

        //compact crée un tableau associatif en recherchant les variables passées sous forme de chaîne
        return $this->render('idea/detail.html.twig', compact("idea", "pif"));
    }

    /**
     * @Route("/ideas/add", name="idea_add", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $idea = new Idea();
        //les données manquantes sont renseignées directement dans l'entité ! voir le PrePersist()

        $form = $this->createForm(IdeaType::class, $idea);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($idea);
            $em->flush();

            $this->addFlash("success", "Idea successfully added!");
            return $this->redirectToRoute("idea_detail", ["id" => $idea->getId()]);
        }

        return $this->render("idea/add.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
