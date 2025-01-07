# 🎵 Artistify

**Artistify** est une application web développée avec Symfony, Twig et MySQL, hébergée dans un environnement Docker. Cette application utilise l'API de Spotify pour permettre aux utilisateurs de rechercher des artistes, consulter leurs profils, leurs albums, et gérer leurs favoris.

## 🚀 Fonctionnalités

- **Recherche d'artistes** : Effectuez une recherche dans la base de données Spotify.
- **Profil d'artiste** : Accédez aux informations détaillées sur un artiste sélectionné.
- **Albums** : Accédez aux albums de l'artiste. -**Tracks**: Accédez aux titres de chaque albums.
- **Gestion des favoris** : Ajoutez ou supprimez des artistes de vos favoris.
- **Liste des favoris** : Accédez à la page des favoris répertoriant tous vos artistes et albums favoris enregistrés en base de données.

---

**Mettez à jour les variables suivantes dans .env :**

- SPOTIFY_CLIENT_ID : Votre clé client Spotify.
- SPOTIFY_CLIENT_SECRET : Votre clé secrète Spotify.
- DATABASE_URL : Connexion MySQL (déjà configurée pour Docker).

1.  docker-compose up --build -d
2.  docker exec -it symfony_app composer install
3.  docker exec -it symfony_app php bin/console doctrine:migrations:migrate
4.  **Accédez à l'application dans votre navigateur** à http://localhost:8080.

## 📄 Structure du projet

- **Backend** : Symfony 6
- **Frontend** : Twig pour les vues.
- **Base de données** : MySQL (géré via Doctrine ORM).
- **API externe** : [Spotify Web API](https://developer.spotify.com/documentation/web-api/).

## 📚 Usage

### Rechercher un artiste

- Utilisez la barre de recherche pour trouver un artiste en fonction de son nom.

### Consulter un profil

- Cliquez sur un artiste pour afficher son profil détaillé.

### Ajouter un artiste en favori

- Depuis la page de profil d'un artiste, cliquez sur "S'abonner".

### Consulter les albums

- Depuis la page profil d'un artiste, vous accédez à la liste des ses albums/singles

## Consutler les morceaux des albums

- Cliquez sur un album pour afficher les détails.

### Ajouter un album en favori

- Depuis la page d'un album, cliquez sur "Ajouter aux favoris".

### Supprimer un favori

- Cliquez sur "Se désabonner" ou "Supprier des favoris" pour retirer un artiste ou album de vos favoris.

## 🐳 Configuration Docker

Le fichier docker-compose.yml configure les services suivants :

- **Symfony** : Serveur PHP (basé sur PHP-FPM) avec Nginx.
- **MySQL** : Base de données pour stocker les favoris.
- **phpmyadmin** : Interface graphique pour gérer MySQL (optionnel).

### Commandes utiles

- docker-compose up -d
- docker-compose up --build -d
- docker-compose down

## 🛡️ Sécurité

- Assurez-vous de ne pas exposer vos clés Spotify (SPOTIFY_CLIENT_ID, SPOTIFY_CLIENT_SECRET) dans des fichiers versionnés.
