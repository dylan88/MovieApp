<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/genre")
     */
    public function list(GenreRepository $repo): Response
    {
        $genres = $repo->findAllNames();
        return $this->render('genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }
}
