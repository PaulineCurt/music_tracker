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
        $name = $request->request->get('name');
        $image = $request->request->get('image');

        if ($name === null || $image === null) {
            // Gérer le cas où les paramètres sont manquants
            $this->addFlash('error', 'Les paramètres name et image sont requis.');
            return $this->redirectToRoute('artist_show', ['id' => $id]);
        }

        // Vérifier si l'artiste existe déjà dans les favoris
        $existingFavorite = $this->entityManager->getRepository(FavoriteArtist::class)->findOneBy(['artistId' => $id]);

        if ($existingFavorite) {
            // Gérer le cas où l'artiste est déjà dans les favoris
            $this->addFlash('error', 'Cet artiste est déjà dans vos favoris.');
            return $this->redirectToRoute('artist_show', ['id' => $id]);
        }

        // Créer un nouvel objet FavoriteArtist et le sauvegarder dans la base de données
        $favoriteArtist = new FavoriteArtist();
        $favoriteArtist->setArtistId($id);
        $favoriteArtist->setName($name);
        $favoriteArtist->setImage($image);

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

    /**
     * @Route("/artist/unfavorite/{id}", name="artist_unfavorite", methods={"POST"})
     */
    public function removeFavoriteArtist($id): Response
    {
        $favoriteArtist = $this->entityManager->getRepository(FavoriteArtist::class)->findOneBy(['artistId' => $id]);

        if ($favoriteArtist) {
            $this->entityManager->remove($favoriteArtist);
            $this->entityManager->flush();
            $this->addFlash('success', 'Artiste supprimé des favoris.');
        } else {
            $this->addFlash('error', 'Cet artiste n\'est pas dans vos favoris.');
        }

        return $this->redirectToRoute('artist_show', ['id' => $id]);
    }
}
