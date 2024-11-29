<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SpotifyService
{
    private $client;
    private $params;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->params = $params;
    }

    public function searchArtists(string $query): array
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/search', [
            'query' => [
                'q' => $query,
                'type' => 'artist',
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ],
        ]);

        $data = $response->toArray();

        return $data['artists']['items'] ?? [];
    }

    public function getArtist(string $id): array
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/artists/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ],
        ]);

        return $response->toArray();
    }

    private function getAccessToken(): string
    {
        $clientId = $this->params->get('spotify_client_id');
        $clientSecret = $this->params->get('spotify_client_secret');

        $response = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
            'body' => [
                'grant_type' => 'client_credentials',
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
            ],
        ]);

        $data = $response->toArray();

        return $data['access_token'];
    }
}
