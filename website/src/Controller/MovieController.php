<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Services\OmdbApiConsumer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movies")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/latest/{limit<\d+>}")
     */
    public function latest(MovieRepository $repo, int $limit = 5): Response
    {
        $movies = $repo->findLatest($limit);
        return $this->render('movie/latest.html.twig', [
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/by-genre")
     */
    public function byGenre(): Response
    {
        return $this->render('movie/by_genre.html.twig', [
        ]);
    }

    /**
     * @Route("/toprated")
     */
    public function topRated(): Response
    {
        return $this->render('movie/top_rated.html.twig', [
        ]);
    }

    /**
     * @Route("/order/{id<\d{1,3}>}")
     */
    public function order(Movie $movie): Response
    {
        $this->denyAccessUnlessGranted('HAS_REQUIRED_AGE', $movie);
    }

    /**
     * @Route("/add")
     * @IsGranted("ROLE_USER")
     */
    public function addMovie(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MovieType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $movie Movie */
            $movie = $form->getData();
            $em->persist($movie);
            $em->flush();
            $this->addFlash('notice', 'You add a new movie');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('movie/add-movie.html.twig', [
           'movie_form' => $form->createView()
        ]);
    }
}
