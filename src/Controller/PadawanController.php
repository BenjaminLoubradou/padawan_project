<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\GitHubGraphQL;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PadawanController extends AbstractController
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/padawan/profil/{id}", name="padawan_profil")
     */
    public function profil(Request $request)
    {
        $padawan = $this->userRepository->find($request->get('id'));

        //récupérer les projets auxquels le padawan participe
        $participations = $padawan->getParticipations();

        //Pour chaque participations, on récupère les commits
        $projets_commit = [];
        foreach ($participations as $participation) {
            $github_api = new GitHubGraphQL();
            $datas = $github_api->getCommits($participation->getGithubRepository(),$padawan->getGithub(),5);
//            dd($datas);
            $total_commits = $datas['data']['repository']['object']['history']['totalCount'];
            $commits = $datas['data']['repository']['object']['history']['nodes'];
//            dd($total_commits,$commits);
            $datas_clean = [
                'nom'=>$participation->getProject()->getNom(),
                'commits'=>$commits,
                'total_commits'=>$total_commits,
                'date_inscription'=>$participation->getDateInscription(),
                'repository'=>$participation->getGithubRepository()];
            $projects_commits[] = $datas_clean;
        }



        return $this->render('padawan/profil.html.twig', [
            'padawan' => $padawan, 'projects'=>$projects_commits
        ]);
    }
}
