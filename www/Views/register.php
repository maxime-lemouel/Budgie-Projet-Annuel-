<?php
$errors = json_decode($errors ?? '[]', true);
$success = ($success ?? 'false') === 'true';
?>

<div class="card card--auth">

    <div class="logo-wrapper logo-wrapper--sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M5.121 17.804A9 9 0 1118.879 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>

    <div>
        <h1 class="page-title">S'inscrire</h1>
        <p class="page-subtitle">Créez votre compte Budgie</p>
    </div>

    <?php if ($success): ?>

        <div class="alert alert--success">Inscription réussie ! Email de confirmation envoyé.</div>
        <div class="footer-links">
            <a href="/login" class="link link--blue">Retour à la connexion</a>
        </div>

    <?php else: ?>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <form class="form form--compact" method="post">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="firstname">Prénom</label>
                    <input class="input" type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="lastname">Nom</label>
                    <input class="input" type="text" id="lastname" name="lastname" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input class="input" type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="pwd">Mot de passe</label>
                <input class="input" type="password" id="pwd" name="pwd" required minlength="8">
            </div>
            <div class="form-group">
                <label class="form-label" for="pwdConfirm">Confirmer le mot de passe</label>
                <input class="input" type="password" id="pwdConfirm" name="pwdConfirm" required minlength="8">
            </div>
            <button class="button button--primary button--full" type="submit">S'inscrire</button>
        </form>

        <div class="footer-links">
            <span>Déjà un compte ?</span>
            <a href="/login" class="link">Se connecter</a>
            <br><br>
            <a href="/contact" class="link link--blue">Nous contacter</a>
        </div>

    <?php endif; ?>

</div>