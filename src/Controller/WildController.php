<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild/", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("index", name="index")
     */
    public function index() : Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("show/{slug<[a-z0-9-]+>}", defaults={"slug" = null}, name="show")
     */
    public function show($slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('Aucune série sélectionnée, veuillez choisir une série');
        }
            $slug = preg_replace("/-/", " ", $slug);
            $slug = ucwords(strtolower($slug));
            $slug = trim($slug, "-");

        return $this->render('wild/show.html.twig', ['slug' => $slug]);
    }
}
