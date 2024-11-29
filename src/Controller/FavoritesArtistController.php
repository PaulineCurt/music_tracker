<?php

namespace App\Controller;

use App\Entity\FavoriteArtist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesArtistController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/artist/favorite/{id}", name="artist_favorite", methods={"POST"})
     */
    public function favoriteArtist(Request $request, $id): Response
    {
        // Créer un nouvel objet FavoriteArtist et le sauvegarder dans la base de données
        $favoriteArtist = new FavoriteArtist();
        $favoriteArtist->setArtistId($id);

        $this->entityManager->persist($favoriteArtist);
        $this->entityManager->flush();

        // Rediriger ou retourner une réponse appropriée
        return $this->redirectToRoute('get_favorites');
    }

    /**
     * @Route("/favorites", name="get_favorites")
     */
    public function getFavorites(): Response
    {
        // Récupérer tous les artistes favoris depuis la base de données
        $favorites = $this->entityManager->getRepository(FavoriteArtist::class)->findAll();

        // Passer les données à la vue
        return $this->render('favorites/favorites.html.twig', [
            'favorites' => $favorites,
        ]);
    }
}
