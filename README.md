# Budgie
> Votre partenaire financier personnel — suivi de dépenses sans connexion bancaire.

---

## Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (inclut Docker Compose)
- [Git](https://git-scm.com/)
- [Node.js 18+](https://nodejs.org/) (pour compiler le SCSS)

---

## Installation & lancement en local

### 1. Cloner le projet

```bash
git clone https://github.com/maxime-lemouel/Budgie-Projet-Annuel-.git
cd Budgie-Projet-Annuel-
```

### 2. Créer le fichier d'environnement

```bash
cp .env.example .env
```

Le fichier `.env` par défaut pour le développement local :

```env
POSTGRES_USER=devuser
POSTGRES_PASSWORD=devpass
POSTGRES_DB=devdb

PGADMIN_DEFAULT_EMAIL=admin@example.com
PGADMIN_DEFAULT_PASSWORD=admin123
```

> Vous pouvez modifier ces valeurs si vous le souhaitez, mais elles fonctionnent telles quelles en local.

### 3. Installer les dépendances PHP

```bash
docker run --rm -v $(pwd)/www:/app composer:latest install --working-dir=/app
```

> Cette commande génère le dossier `www/vendor/` sans avoir besoin d'installer PHP ni Composer sur votre machine.

### 4. Construire et démarrer les conteneurs

```bash
docker compose up -d --build
```

### 5. Initialiser la base de données

```bash
docker compose exec db psql -U devuser -d devdb -f /dev/stdin < tables.sql
```

### 6. Accéder à l'application

| Service | URL | Description |
|---------|-----|-------------|
| **Application** | http://localhost:8080 | Site Budgie |
| **pgAdmin** | http://localhost:5050 | Interface base de données |
| **MailHog** | http://localhost:8025 | Boîte mail de test |

---

## Connexion à pgAdmin (interface base de données)

1. Ouvrez http://localhost:5050
2. Connectez-vous avec `admin@example.com` / `admin123`
3. Cliquez sur **Add New Server** :
   - **Name** : `Budgie Dev`
   - **Host** : `db`
   - **Port** : `5432`
   - **Username** : `devuser`
   - **Password** : `devpass`

---

## Emails en développement (MailHog)

En local, les emails ne sont pas envoyés réellement. Ils sont interceptés par **MailHog**.

Pour voir les emails envoyés par l'application (confirmation d'inscription, reset de mot de passe...) :
→ Ouvrez http://localhost:8025

---

## Travailler sur le SCSS

Les styles sont écrits en SCSS sur la branche `scss` et compilés vers `www/dist/css/`.

### Structure SCSS

```
src/css/
├── main.scss              → compile vers dist/css/main.css   (pages publiques)
├── app.scss               → compile vers dist/css/app.css    (app connectée)
├── admin.scss             → compile vers dist/css/admin.css  (backoffice)
├── components/            # Composants réutilisables
│   ├── _button.scss
│   ├── _card.scss
│   ├── _form.scss
│   ├── _modal.scss
│   ├── _sidebar.scss
│   └── ...
└── partials/              # Variables, mixins, fonctions globales
    ├── _variables.scss
    ├── _mixins.scss
    ├── _functions.scss
    └── ...
```

### Installer et lancer la compilation

```bash
# Se placer sur la branche scss
git checkout scss

# Installer les dépendances Node
npm install

# Compiler en mode watch (recompile automatiquement à chaque modification)
npm run watch
```

La commande `watch` lance 3 compilateurs en parallèle :

| Commande | Source | Destination |
|----------|--------|-------------|
| `watch:main` | `src/css/main.scss` | `dist/css/main.css` |
| `watch:app` | `src/css/app.scss` | `dist/css/app.css` |
| `watch:admin` | `src/css/admin.scss` | `dist/css/admin.css` |

### Workflow SCSS → main

Une fois vos modifications SCSS faites et les CSS compilés :

```bash
# Sur la branche scss — commiter les sources ET les CSS compilés
git checkout scss
git add src/ dist/
git commit -m "style: ..."
git push origin scss

# Récupérer uniquement les CSS compilés dans main (pas les sources SCSS)
git checkout main
git checkout scss -- dist/css/
git commit -m "style: mise à jour des CSS compilés"
git push origin main
```

> ⚠️ Ne faites jamais `git merge scss` depuis `main` — cela copierait `src/`, `node_modules/` et `package.json` dans `main`. Utilisez toujours `git checkout scss -- dist/css/`.

---

## Commandes utiles

```bash
# Démarrer les conteneurs
docker compose up -d

# Arrêter les conteneurs
docker compose down

# Reconstruire après modification du Dockerfile
docker compose up -d --build

# Voir les logs de l'application
docker compose logs web

# Voir les logs en temps réel
docker compose logs -f web

# Accéder au shell du conteneur web
docker compose exec web bash

# Accéder à PostgreSQL en ligne de commande
docker compose exec db psql -U devuser -d devdb

# Réinitialiser la base de données
docker compose down -v   # supprime les volumes
docker compose up -d
docker compose exec db psql -U devuser -d devdb -f /dev/stdin < tables.sql
```

---

## Structure du projet

```
Budgie/
├── www/
│   ├── Controllers/     # Logique métier (Auth, Compte, Dépense, Revenu...)
│   ├── Core/            # Database, Render
│   ├── Helpers/         # Mailer, Clean
│   ├── Models/          # Modèles de données
│   ├── Views/           # Templates HTML PHP
│   ├── public/          # Point d'entrée (index.php) et assets JS
│   ├── dist/            # CSS compilés
│   ├── config.php       # Configuration globale (lit les variables d'env)
│   ├── routes.yml       # Définition des routes
│   └── composer.json    # Dépendances PHP
├── docker/
│   └── apache/
│       └── 000-default.conf  # Config Apache (branche production)
├── Dockerfile           # Image PHP 8.2 + Apache
├── docker-compose.yml   # Orchestration des conteneurs
├── php.ini              # Configuration PHP personnalisée
├── tables.sql           # Schéma de la base de données
├── .env                 # Variables d'environnement (non commité)
├── .env.example         # Template des variables d'environnement
└── .gitignore
```

---

## Stack technique

| Technologie | Version | Rôle |
|-------------|---------|------|
| PHP | 8.2 | Langage backend |
| Apache | 2.4 | Serveur web |
| PostgreSQL | 15 | Base de données |
| PHPMailer | 6.9 | Envoi d'emails |
| Docker | - | Conteneurisation |
| Dart Sass | 1.97+ | Compilation SCSS → CSS |
| Node.js | 18+ | Outillage front (npm scripts) |
| MailHog | - | Emails de test (dev uniquement) |
| pgAdmin | 4 | Interface BDD (dev uniquement) |

---

## Branches

| Branche | Rôle |
|---------|------|
| `main` | Développement local — source de vérité |
| `scss` | Édition des styles SCSS |
| `production` | Déployée sur le serveur (AWS EC2) |

Flux entre les branches :

```
scss ──► main ──► production
```

Pour déployer une nouvelle version en production :

```bash
git checkout production
git merge main
git push origin production

# Sur le serveur EC2
git pull origin production
docker compose up -d --build
```