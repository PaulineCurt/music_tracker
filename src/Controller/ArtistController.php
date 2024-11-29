<?php
// src/Controller/ArtistController.php
namespace App\Controller;

use App\Service\SpotifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\FavoriteArtist;

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
     * @Route("/search", name="artist_search")
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

        // Vérifiez si l'artiste est déjà dans les favoris
        $isFavorite = $this->entityManager->getRepository(FavoriteArtist::class)->findOneBy(['artistId' => $id]) !== null;

        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
            'hasImage' => $hasImage,
            'isFavorite' => $isFavorite,
        ]);
    }
}
