<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramSearchType;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * Show all row from Program's entity
     *
     * @Route("/", name="index")
     * @param Request $request
     * @return Response A response instance
     */
    public function index(): Response
    {
        $form = $this->createForm(ProgramSearchType::class, null,
            ['method' => Request::METHOD_GET]
        );

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException('No program found in program\'s table.');
        }
        return $this->render('wild/index.html.twig', [
                'programs' => $programs,
                'form' => $form->createView(),
            ]
        );

    }



    /**
     *
     * @param string $categoryName
     * @Route("/category/{categoryName}", name="show_category")
     * @return Response
     */
    public function showByCategory(string $categoryName):Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);
        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );

    }


    /**
     * @Route("/show/{slug}", defaults={"slug" = null}, name="program_slug")
     * @param string $slug
     * @return Response
     */
    public function showByProgram(?string $slug): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        $seasons = $program->getSeasons();
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'    => $slug,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @param integer $seasonId
     * @Route ("/show/season/{seasonId}", name="season")
     * @return Response A Response instance
     */
    public function showBySeason(int $seasonId)
    {
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $seasonId]);
        $episodes = $seasons->getEpisodes();
        $program = $seasons->getProgram();
        return $this->render('wild/season.html.twig', [
            'program' => $program,
            'episodes' => $episodes,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @param integer $id
     * @Route ("/show/episode/{id}", name="episode")
     * @return Response A Response instance
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        return $this->render("wild/episode.html.twig", [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
        ]);
    }

}