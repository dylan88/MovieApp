<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Services\OmdbApiConsumer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    private OmdbApiConsumer $api;

    private GenreRepository $genreRepository;

    public function __construct(ManagerRegistry $registry, OmdbApiConsumer $api, GenreRepository $genreRepository)
    {
        parent::__construct($registry, Movie::class);
        $this->api = $api;
        $this->genreRepository = $genreRepository;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Movie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Movie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param int $limit
     * @return array
     */
    public function findLatest(int $limit = 5) : array {
        return $this->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $title
     * @return Movie|null
     */
    public function findMovieByTitleFromApi(string $title) : ?Movie {
        $result = $this->api->fetchMovieByTitle($title);
        if (!$result) {
            return null;
        }
        $movie = Movie::fromArray($result);
        $genres = $result['Genre'];
        $genres = explode(', ',$genres);
        foreach ($genres as $genre) {
            $genreEntity = $this->genreRepository->findOneBy(['name' => $genre]);
            $movie->addGenre($genreEntity);
        }
        return $movie;
    }
}
