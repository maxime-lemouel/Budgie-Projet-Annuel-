<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="dist/css/login.css">
</head>
<body>

<div class="card card--auth">

    <div class="logo-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5m0 0h-6m6 0a2 2 0 01-2 2h-4"/>
        </svg>
    </div>

    <div>
        <h1 class="page-title">Budgie</h1>
        <p class="page-subtitle">Gérez vos finances en toute simplicité</p>
    </div>

    <?php
    $errors = json_decode($errors ?? '[]', true);
    if (!empty($errors)) :
        foreach ($errors as $error) :?>

        <div class="alert alert--error"><?php print_r($error); ?></div>
    <?php endforeach;
    endif; ?>

    <form class="form" method="post">

        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input class="input" type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Mot de passe</label>
            <input class="input" type="password" id="password" name="password" placeholder="••••••••" required>
        </div>

        <button class="button button--full" type="submit">Se connecter</button>

    </form>

    <div class="footer-links">
        <span>Pas encore de compte ?</span>
        <a href="/register" class="link">S'inscrire</a>
        <br>
        <br>
        <a href="/forgot-password" class="link">Mot de passe oublié ?</a>
    </div>



</div>

</body>
</html>