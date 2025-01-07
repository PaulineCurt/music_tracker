<?php

namespace App\Controller;

use App\Entity\FavoriteArtist;
use App\Entity\FavoriteAlbum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/favorites/all", name="get_all_favorites")
     */
    public function getAllFavorites(Request $request): Response
    {
        // Récupérer les favoris des deux entités
        $favoriteArtists = $this->entityManager->getRepository(FavoriteArtist::class)->findAll();
        $favoriteAlbums = $this->entityManager->getRepository(FavoriteAlbum::class)->findAll();

        // Récupérer le filtre depuis la requête
        $filter = $request->query->get('filter', 'all'); // Par défaut : 'all'

        // Appliquer le filtre
        $favorites = [];
        switch ($filter) {
            case 'artists':
                $favorites = $favoriteArtists;
                break;
            case 'albums':
                $favorites = $favoriteAlbums;
                break;
            default:
                $favorites = array_merge($favoriteArtists, $favoriteAlbums);
                break;
        }

        // Passer les favoris et le filtre à la vue
        return $this->render('favorites/favorites.html.twig', [
            'favorites' => $favorites,
            'filter' => $filter,
        ]);
    }
}
