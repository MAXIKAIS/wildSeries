<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/show/{slug<[a-z0-9.-]*>}", name="show_slug")
     */
    public function show(string $slug) :Response
    {
        if(!empty($slug)){
            $slugShow = str_replace('-', ' ', $slug);
            $slugShow = ucwords($slugShow);
        }else{
            $slugShow = 'Aucune serie selectionnee, veuillez choisir une serie';
        }

        return $this->render('wild/show.html.twig', [
            'slugShow' => $slugShow,
        ]);
    }
}