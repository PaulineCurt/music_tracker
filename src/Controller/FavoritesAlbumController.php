<?php

namespace App\Controller;

use App\Entity\FavoriteAlbum;
use App\Service\SpotifyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesAlbumController extends AbstractController
{
    private $entityManager;
    private $spotifyService;

    public function __construct(EntityManagerInterface $entityManager, SpotifyService $spotifyService)
    {
        $this->entityManager = $entityManager;
        $this->spotifyService = $spotifyService;
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

        // Vérifier si l'album est déjà en favoris
        $isFavorite = $this->entityManager->getRepository(FavoriteAlbum::class)->findOneBy(['album_id' => $id]) !== null;

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
            'isFavorite' => $isFavorite,
        ]);
    }

    /**
     * @Route("/album/favorite/{id}", name="album_favorite", methods={"POST"})
     */
    public function favoriteAlbum(Request $request, $id): Response
    {
        $title = $request->request->get('title');
        $image = $request->request->get('image');

        if ($title === null || $image === null) {
            // Gérer le cas où les paramètres sont manquants
            $this->addFlash('error', 'Les paramètres title et image sont requis.');
            return $this->redirectToRoute('album_show', ['id' => $id]);
        }

        // Vérifier si l'album existe déjà dans les favoris
        $existingFavorite = $this->entityManager->getRepository(FavoriteAlbum::class)->findOneBy(['album_id' => $id]);

        if ($existingFavorite) {
            // Gérer le cas où l'album est déjà dans les favoris
            $this->addFlash('error', 'Cet album est déjà dans vos favoris.');
            return $this->redirectToRoute('album_show', ['id' => $id]);
        }

        // Créer un nouvel objet FavoriteAlbum et le sauvegarder dans la base de données
        $favoriteAlbum = new FavoriteAlbum();
        $favoriteAlbum->setAlbumId($id);
        $favoriteAlbum->setTitle($title);
        $favoriteAlbum->setImage($image);

        $this->entityManager->persist($favoriteAlbum);
        $this->entityManager->flush();

        // Rediriger ou retourner une réponse appropriée
        return $this->redirectToRoute('album_show', ['id' => $id]);
    }

    /**
     * @Route("/album/unfavorite/{id}", name="album_unfavorite", methods={"POST"})
     */
    public function unfavoriteAlbum($id): Response
    {
        $favoriteAlbum = $this->entityManager->getRepository(FavoriteAlbum::class)->findOneBy(['album_id' => $id]);

        if ($favoriteAlbum) {
            $this->entityManager->remove($favoriteAlbum);
            $this->entityManager->flush();
            $this->addFlash('success', 'Album supprimé des favoris.');
        } else {
            $this->addFlash('error', 'Cet album n\'est pas dans vos favoris.');
        }

        return $this->redirectToRoute('album_show', ['id' => $id]);
    }
}
