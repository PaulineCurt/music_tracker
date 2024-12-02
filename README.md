# 🎵 Symfony Spotify App

**Symfony Spotify App** est une application web développée avec Symfony, Twig et MySQL, hébergée dans un environnement Docker. Cette application utilise l'API de Spotify pour permettre aux utilisateurs de rechercher des artistes, consulter leurs profils et gérer leurs favoris.

## 🚀 Fonctionnalités

- **Recherche d'artistes** : Effectuez une recherche dans la base de données Spotify.
- **Profil d'artiste** : Accédez aux informations détaillées sur un artiste sélectionné.
- **Gestion des favoris** : Ajoutez ou supprimez des artistes de vos favoris.
- **Liste des favoris** : Accédez à une page `/favorites` répertoriant tous vos artistes favoris enregistrés en base de données.

---

**Mettez à jour les variables suivantes dans .env :**

*   SPOTIFY\_CLIENT\_ID : Votre clé client Spotify.
    
*   SPOTIFY\_CLIENT\_SECRET : Votre clé secrète Spotify.
    
*   DATABASE\_URL : Connexion MySQL (déjà configurée pour Docker).
    

1.  Copier le codedocker-compose up --build -d
    
2.  Copier le codedocker exec -it symfony\_app composer install
    
3.  Copier le codedocker exec -it symfony\_app php bin/console doctrine:migrations:migrate
    
4.  Copier le codedocker exec -it symfony\_app php bin/console doctrine:fixtures:load
    
5.  **Accédez à l'application dans votre navigateur** à http://localhost:8080.
    

📄 Structure du projet
----------------------

*   **Backend** : Symfony 6
    
*   **Frontend** : Twig pour les vues.
    
*   **Base de données** : MySQL (géré via Doctrine ORM).
    
*   **API externe** : [Spotify Web API](https://developer.spotify.com/documentation/web-api/).
    

📚 Usage
--------

### Rechercher un artiste

*   Utilisez la barre de recherche pour trouver un artiste en fonction de son nom.
    

### Consulter un profil

*   Cliquez sur un artiste pour afficher son profil détaillé.
    

### Ajouter un favori

*   Depuis la page de profil d'un artiste, cliquez sur "Ajouter aux favoris".
    

### Supprimer un favori

*   Depuis la page /favorites, cliquez sur "Supprimer" pour retirer un artiste de vos favoris.
    

🐳 Configuration Docker
-----------------------

Le fichier docker-compose.yml configure les services suivants :

*   **Symfony** : Serveur PHP (basé sur PHP-FPM) avec Nginx.
    
*   **MySQL** : Base de données pour stocker les favoris.
    
*   **phpmyadmin** : Interface graphique pour gérer MySQL (optionnel).
    

### Commandes utiles

*   Copier le codedocker-compose up -d
    
*   Copier le codedocker-compose down
    
*   Copier le codedocker exec -it symfony\_app bash
    

🛡️ Sécurité
------------

*   Assurez-vous de ne pas exposer vos clés Spotify (SPOTIFY\_CLIENT\_ID, SPOTIFY\_CLIENT\_SECRET) dans des fichiers versionnés.
