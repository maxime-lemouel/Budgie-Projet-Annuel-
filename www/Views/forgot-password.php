<?php
$errors  = json_decode($errors ?? '[]', true);
$success = ($success ?? 'false') === 'true';
?>

<div class="card card--auth card--auth-sm">

    <div>
        <h1 class="page-title">Mot de passe oublié</h1>
    </div>

    <?php if ($success): ?>

        <div class="alert alert--success">Vous recevrez un lien de réinitialisation sous peu.</div>
        <div class="footer-links">
            <a href="/login" class="link link--blue">← Retour à la connexion</a>
        </div>

    <?php else: ?>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <p class="page-description">Entrez votre email pour recevoir un lien de réinitialisation.</p>

        <form class="form" method="post">
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input class="input" type="email" id="email" name="email" required>
            </div>
            <button class="button button--full" type="submit">Envoyer le lien</button>
        </form>

        <div class="footer-links">
            <a href="/login" class="link link--blue">← Retour à la connexion</a>
            <br><br>
            <a href="/contact" class="link link--blue">Nous contacter</a>
        </div>

    <?php endif; ?>

</div>