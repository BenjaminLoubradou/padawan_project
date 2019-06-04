<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project", name="project")
     */
    public function index()
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

    /**
     * @Route("/project/add",name="project_add")
     */
    public function add(Request $request){
        // création de l'objet à persister dans la db
        $project = new Project();
        // creation de l'objet form qui va  générer le html dans la vue et prendre en charge la validation du formulaire et l'hydratation de l'objet $project avec les datas dans le form
        // créa de l'objet form
        $form = $this->createForm(ProjectType::class,$project);
        //Hydratation de l'objet avec les datas du form(objet $request)
        $form->handleRequest($request);

        // si validation du form et form valide (toutes les données sont saisies correctement > champ oblifatoires etc ...
        if($form->isSubmitted() && $form->isValid()){
            // recupere l'entity manager de doctrine (ORM)
            $em = $this->getDoctrine()->getManager();
            // Association de user loggé et du projet qu'il est en train de créer
            $project->setProposePar(($this->getUser()));
            $em->flush();
            //ajouter un message flash dans la session
            $this->addFlash('success','Merci ! Votre projet a été proposé au maitre Jedi');
            // return redirection vers la page de confirmation de création de projet
            return $this->redirectToRoute('home');
        }

        //Affichage de la vue
        return $this->render('project/add.html.twig',['form'=>$form->createView()]);
    }
}
