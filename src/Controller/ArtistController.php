<?php
// src/Controller/ArtistController.php
namespace App\Controller;

use App\Service\SpotifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\FavoriteArtist;
use Doctrine\ORM\EntityManagerInterface;

class ArtistController extends AbstractController
{
    private $spotifyService;
    private $entityManager;

    public function __construct(SpotifyService $spotifyService, EntityManagerInterface $entityManager)
    {
        $this->spotifyService = $spotifyService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/artist/search", name="artist_search")
     */
    public function search(Request $request): Response
    {
        $query = $request->query->get('q', '');

        $artists = [];
        if ($query) {
            $artists = $this->spotifyService->searchArtists($query);
        }

        return $this->render('artist/search.html.twig', [
            'artists' => $artists,
            'query' => $query,
        ]);
    }
    /**
     * @Route("/artist/{id}", name="artist_show")
     */
    public function show(string $id): Response
    {
        $artist = $this->spotifyService->getArtist($id);

        // Vérifiez si l'artiste a des images
        $hasImage = !empty($artist['images']);

        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
            'hasImage' => $hasImage,
        ]);
    }

    /**
     * @Route("/artist/{id}/favorite", name="artist_favorite", methods={"POST"})
     */
    public function favorite(string $id): Response
    {
        try {
            $favorite = new FavoriteArtist();
            $favorite->setArtistId($id);

            $this->entityManager->persist($favorite);
            $this->entityManager->flush();

            $this->addFlash('success', 'Artiste ajouté aux favoris');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'ajout de l\'artiste aux favoris');
        }

        return $this->redirectToRoute('artist_show', ['id' => $id]);
    }
    /**
     * @Route("/favorites", name="favorites")
     */
    public function favorites(): Response
    {
        $favorites = $this->entityManager->getRepository(FavoriteArtist::class)->findAll();

        $artistIds = array_map(function ($favorite) {
            return $favorite->getArtistId();
        }, $favorites);

        if (empty($artistIds)) {
            return $this->render('artist/favorites.html.twig', [
                'artists' => [],
            ]);
        }

        $artists = $this->spotifyService->getArtists($artistIds);

        return $this->render('artist/favorites.html.twig', [
            'artists' => $artists,
        ]);
    }
}
