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
            foreach ($artists as &$artist) {
                $artist['image'] = !empty($artist['images']) ? $artist['images'][0]['url'] : null;
            }
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

        // Récupérez les albums de l'artiste
        $albums = $this->spotifyService->getArtistAlbums($id);

        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
            'hasImage' => $hasImage,
            'isFavorite' => $isFavorite,
            'albums' => $albums,
        ]);
    }

    /**
     * @Route("/album/{id}", name="album_show")
     */
    public function showAlbum(string $id): Response
    {
        $album = $this->spotifyService->getAlbum($id);

        // Récupération des pistes
        $tracks = array_map(function ($track) {
            return [
                'name' => $track['name'],
                'duration_ms' => $track['duration_ms'],
                'preview_url' => $track['preview_url'] ?? null,
            ];
        }, $album['tracks']['items']);

        // Autres données nécessaires
        $totalTracks = $album['total_tracks'];
        $img = !empty($album['images']) ? $album['images'][0]['url'] : null;
        $artist = $album['artists'][0];
        $releaseYear = (new \DateTime($album['release_date']))->format('Y');
        $albumType = $album['album_type'];

        // Calcul de la durée totale en millisecondes
        $totalDurationMs = array_reduce($tracks, function ($carry, $track) {
            return $carry + $track['duration_ms'];
        }, 0);

        // Conversion de la durée totale en heures et minutes
        $totalDurationMinutes = floor($totalDurationMs / 60000);
        $totalDurationHours = floor($totalDurationMinutes / 60);
        $totalDurationMinutes = $totalDurationMinutes % 60;

        return $this->render('album/show.html.twig', [
            'album' => $album,
            'tracks' => $tracks,
            'totalTracks' => $totalTracks,
            'img' => $img,
            'artist' => $artist,
            'releaseYear' => $releaseYear,
            'totalDurationHours' => $totalDurationHours,
            'totalDurationMinutes' => $totalDurationMinutes,
            'albumType' => $albumType,
        ]);
    }
}
