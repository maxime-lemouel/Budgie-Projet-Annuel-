# Budgie — Styles SCSS

Ce dossier contient les sources SCSS du projet Budgie.  
Les fichiers CSS compilés (`dist/css/`) sont ensuite utilisés par la branche `main`.

---

## Prérequis

- [Node.js 18+](https://nodejs.org/)
- npm (inclus avec Node.js)

---

## Installation

```bash
npm install
```

---

## Compilation

### Mode watch (développement)
Recompile automatiquement à chaque modification :

```bash
npm run watch
```

Lance 3 compilateurs en parallèle :

| Commande | Source | Destination |
|----------|--------|-------------|
| `watch:main` | `src/css/main.scss` | `dist/css/main.css` |
| `watch:app` | `src/css/app.scss` | `dist/css/app.css` |
| `watch:admin` | `src/css/admin.scss` | `dist/css/admin.css` |

---

## Structure des fichiers

```
src/css/
├── main.scss              → pages publiques (login, register...)
├── app.scss               → application connectée
├── admin.scss             → backoffice
├── components/            # Composants réutilisables
│   ├── _admin.scss
│   ├── _alert.scss
│   ├── _app_layout.scss
│   ├── _badge.scss
│   ├── _button.scss
│   ├── _card.scss
│   ├── _container.scss
│   ├── _data_table.scss
│   ├── _form.scss
│   ├── _grid.scss
│   ├── _link.scss
│   ├── _logo.scss
│   ├── _modal.scss
│   └── _sidebar.scss
└── partials/              # Globals
    ├── _variables.scss
    ├── _mixins.scss
    ├── _functions.scss
    ├── _fonts.scss
    ├── _globals.scss
    ├── _layers.scss
    ├── _utilities.scss
    └── _index.scss
```

---

## Workflow

```bash
# 1. Modifier les fichiers dans src/css/
# 2. npm run watch compile automatiquement vers dist/css/

# 3. Commiter sources ET CSS compilés
git add src/ dist/
git commit -m "style: description de la modification"
git push origin scss

# 4. Récupérer uniquement les CSS compilés dans main
#    (sans copier src/, node_modules/, package.json)
git checkout main
git checkout scss -- dist/css/
git commit -m "style: mise à jour des CSS compilés"
git push origin main

# Retour sur scss pour continuer
git checkout scss
```

> ⚠️ Ne jamais faire `git merge scss` depuis `main` — cela copierait les sources SCSS et `node_modules/` dans `main`. Utilisez toujours `git checkout scss -- dist/css/`.