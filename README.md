# 🚀 Symfony Dockerized Project

Ce projet utilise Docker pour créer un environnement de développement Symfony complet, incluant :

- 🐘 PHP (Symfony)
- 🐬 MySQL (base de données)
- 🌐 Nginx (serveur web)
- 🟢 Node.js (Webpack Encore)

---

## 📦 Prérequis

Assurez-vous d’avoir installé les outils suivants sur votre machine :

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## 🛠 Structure des services

- `php`: exécute Symfony (PHP 8.x avec extensions requises)
- `mysql`: base de données MySQL 8
- `nginx`: serveur web configuré pour Symfony
- `node`: container Node.js pour gérer Webpack Encore (build des assets)

---

## ⚙️ Installation

### 1. Clonez le dépôt

git clone https://github.com/Dorian-Feschaud/todo.git
cd todo


### 2. Copiez les fichiers d’environnement

cp .env.example .env
cp docker/.env.example docker/.env

### 3. Construisez et démarrez les containers

docker-compose up --build -d

### 4. Installez les dépendances PHP

docker-compose exec php composer install

### 5. Installez les dépendances JavaScript et compilez les assets

docker-compose exec node yarn install
docker-compose exec node yarn encore dev

### 6. Créez la base de donnée

docker-compose exec php bin/console doctrine:database:create
docker-compose exec php bin/console doctrine:migrations:migrate



## 🤝 Contribution

### 1. Créer une nouvelle branche

git checkout -b feature/nom-de-ta-feature

Utilise un nom explicite :

feature/ pour une nouvelle fonctionnalité
fix/ pour une correction de bug

### 2. Exécution des tests

docker-compose exec php ./bin/phpunit

Tous les tests doivent être au vert ✅

### 3. Vérification avec PHPStan

docker-compose exec php ./vendor/bin/phpstan analyse src
docker-compose exec php ./vendor/bin/phpstan analyse tests

Corrige les éventuelles erreurs remontées avant de continuer.

### 4. Commit & push

git add .
git commit -m "nouvelle feature"
git push origin feature/nom-de-ta-feature

Indique clairement dans le message ce qui à été modifié et ajoute le numéro de l'issue si possible.

### 5. Création de la Pull Request

Depuis GitHub :

Va sur le dépôt
Clique sur "Compare & pull request"
Donne un titre clair et une description précise
Laisse des commentaires si nécessaire pour expliquer tes choix
Attribue un ou plusieurs relecteurs
Une PR ne doit être mergée qu'après validation et si les tests passent ✅