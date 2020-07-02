<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class MainController extends Controller
{
    /**
    * @Route("/", name="home")
    */
    public function home()
    {
        $repo = $this->getDoctrine()->getRepository(Post::class);
        $latestPosts = $repo->findBy([], ["isFeatured" => "DESC", "datePublished" => "DESC"], 5);

        return $this->render('default/home.html.twig', compact("latestPosts"));
    }

    /**
     * @Route("/about-us", name="about_us")
     */
    public function aboutUs()
    {
        //tp surprise
        //le chemin est relatif au contrôleur front (public/index.php)
        $rawData = file_get_contents("../src/data/team.json");
        //convertie la chaîne en tableau !
        $teamMembers = json_decode($rawData);

        return $this->render('default/about_us.html.twig', [
            //crée une variable teamMembers dans mon fichier twig !
            "teamMembers" => $teamMembers
        ]);
    }

    /**
     * @Route("/create-post", name="create_post", methods={"GET", "POST"})
     */
    public function createPost(Request $request)
    {
        //pour empêcher les non-admin d'accéder à cette page
        //if (!$this->isGranted("ROLE_ADMIN")){
        //    throw $this->createAccessDeniedException("fuck off");
        //}
        //ou identique
        //$this->denyAccessUnlessGranted("ROLE_ADMIN");

        //crée l'entité vide qui sera sauvegardée lorsque ce sera soumis
        $post = new Post();
        //renseigne la date actuelle, car ce champ n'est pas dans le form
        //(il y a un "use" dans le haut du fichier !)
        $post->setDatePublished(new DateTime());

        //crée une instance de notre form, en lui associant notre entité vide
        $postForm = $this->createForm("App\Form\PostType", $post);

        //prend les données soumises par le formulaire et les injecte dans $post
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()){
            //l'entity manager nous permet de faire les insert, update et delete !
            $em = $this->getDoctrine()->getManager();
            //on demande à Doctrine de sauvegarder notre instance en bdd
            $em->persist($post);
            //et on confirme !
            $em->flush();

            //message qui s'affichera sur la prochaine page !
            $this->addFlash("success", "Your post is published!");

            //recharge la page pour vider le form
            return $this->redirectToRoute("home");
        }

        //crée une version de notre form optimisée pour l'affichage dans twig
        $postFormView = $postForm->createView();

        return $this->render("default/create_post.html.twig", compact("postFormView"));
    }
}











