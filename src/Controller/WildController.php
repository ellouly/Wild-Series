<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild/", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("index", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @param string $category
     * @Route("category/{categoryName}", name="show_category")
     * @return Response
     */
    public function showByCategory(string $categoryName) : Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this
                ->createNotFoundException('Please, enter a category to find');
        }

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id'=> 'DESC'], 3);

        return $this->render('wild/category.html.twig', [
            'category' => $category,
            'program' => $program
        ]);
    }
    /**
     * Retrieve a program from a slug passed in the url
     *
     * @param string $slug
     * @Route("program/{slug}", name="program")
     * @return Response
     */
    public function showByProgram(string $slug): Response
    {
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );


        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }
    /**
     * Retrieve all seasons of a program
     *
     * @param int $id,
     * @Route("season/{id}", name="season")
     * @return Response
     */
    public function showBySeason(int $id): Response
    {
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);
        $program = $seasons->getProgram();
        $episodes = $seasons->getEpisodes();
        
        return $this->render('wild/season.html.twig', [
            'seasons' => $seasons,
            'episodes' => $episodes,
            'program' => $program,
        ]);
    }

    /**
     * Retrieve an episode of a program in a season
     *
     * @Route("episode/{id}", name="episode")
     * @return Response
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
        ]);
    }

    /**
     * Getting an actor with the list of his programs
     *
     * @Route("actor/{id}", name="actor")
     * @return Response
     */
    public function showActor(Actor $id): Response{

        $actor = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['id' => $id]);

        $programs = $actor->getPrograms();

        return $this->render('wild/actor.html.twig', [
            'actor' => $actor,
            'programs' => $programs,
        ]);
    }
}
