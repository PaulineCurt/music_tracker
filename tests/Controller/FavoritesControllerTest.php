<?php

namespace App\Tests\Controller;

use App\Entity\FavoriteArtist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FavoritesArtistControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testGetFavoritesReturnsResponse(): void
    {
        // Créer un artiste favori
        $favoriteArtist = new FavoriteArtist();
        $favoriteArtist->setArtistId("6vWDO969PvNqNYHIOW5v0m");
        $favoriteArtist->setName('Beyoncé');
        $favoriteArtist->setImage('https://i.scdn.co/image/ab6761610000e5eb247f44069c0bd1781df2f785');

        // Persister l'entité
        $this->entityManager->persist($favoriteArtist);
        $this->entityManager->flush();

        // Utiliser le client pour faire la requête à la route
        $this->client->request('GET', '/favorites');

        // Récupérer la réponse
        $response = $this->client->getResponse();

        // Assertions
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        // Vérifie que les données sont présentes dans la réponse
        $content = $response->getContent();
        $this->assertStringContainsString('Beyoncé', $content);

        // Vérifie que les données sont bien dans la base de données
        $repository = $this->entityManager->getRepository(FavoriteArtist::class);
        $artist = $repository->findOneBy(['artistId' => '6vWDO969PvNqNYHIOW5v0m']);
        $this->assertNotNull($artist);
        $this->assertEquals('Beyoncé', $artist->getName());
    }

    protected function tearDown(): void
    {
        // Nettoyer la base de données
        $this->entityManager->close();
        $this->entityManager = null; // Éviter les fuites de mémoire
    }
}
