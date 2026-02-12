<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="scss/login.scss">
</head>
<body>
<?php
$errors = json_decode($errors ?? '[]', true);
if (!empty($errors)) {
    echo "<pre>";
    print_r($errors);
    echo "</pre>";
}
?>
<div class="login-container">

    <div class="logo-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5m0 0h-6m6 0a2 2 0 01-2 2h-4"/>
        </svg>
    </div>

    <h1>Budgie</h1>
    <p class="subtitle">Gérez vos finances en toute simplicité</p>

    <form method="post">

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit">Se connecter</button>

    </form>


    <div class="footer-links">
        <span>Pas encore de compte ?</span>
        <a href="/register">S'inscrire</a>
        
    </div>
<a href="/forgot-password">Mot de passe oublié ?</a><br>
</div>

</body>
</html>
