<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="scss/register.scss">
</head>
<body>
    
    <?php
$errors = json_decode($errors ?? '[]', true);
$success = ($success ?? 'false') == 'true';
?>

<div class="login-container">
    <div class="logo-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M5.121 17.804A9 9 0 1118.879 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>

    <h1>S'inscrire</h1>
    <p class="subtitle">Créez votre compte Budgie</p>

    <?php if ($success): ?>
        <p>Inscription réussie ! Email de confirmation envoyé.</p>
        <div class="footer-links">
            <a href="/login">Retour à la connexion</a>
        </div>

    <?php else: ?>
        <?php if (!empty($errors)): ?>
            <pre><?php print_r($errors); ?></pre>
        <?php endif; ?>

            <form method="post">

            <div class="form-row">
                <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" id="firstname" name="firstname" required>
                </div>

                <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" id="lastname" name="lastname" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="pwd">Mot de passe</label>
                <input type="password" id="pwd" name="pwd" required minlength="8">
            </div>

            <div class="form-group">
                <label for="pwdConfirm">Confirmer le mot de passe</label>
                <input type="password" id="pwdConfirm" name="pwdConfirm" required minlength="8">
            </div>

            <button type="submit">S'inscrire</button>

            </form>


        <div class="footer-links">
            <a href="/login">Déjà un compte ? Se connecter</a>
        </div>
    <?php endif; ?>

</div>
