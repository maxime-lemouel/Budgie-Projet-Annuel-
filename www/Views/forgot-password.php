<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="dist/css/forgot.css">
</head>
<body>

<?php
$errors  = json_decode($errors ?? '[]', true);
$success = ($success ?? 'false') == 'true';
?>

<div class="card card--auth card--auth-sm">

    <div>
        <h1 class="page-title">Mot de passe oublié</h1>
    </div>

    <?php if ($success) : ?>

        <div class="alert alert--success">Vous recevrez un lien de réinitialisation sous peu.</div>
        <a href="/login" class="link link--blue">← Retour à la connexion</a>

    <?php else : ?>

        <?php if (!empty($errors)) :
            foreach ($errors as $error) :?>

                <div class="alert alert--error"><?php print_r($error); ?></div>
            <?php endforeach;
        endif; ?>

        <p class="page-description">Entrez votre email pour recevoir un lien de réinitialisation.</p>

        <form class="form" method="post">

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input class="input" type="email" id="email" name="email" required>
            </div>

            <button class="button button--full" type="submit">Envoyer le lien</button>

        </form>

        <a href="/login" class="link link--blue">← Retour à la connexion</a>

    <?php endif; ?>

</div>

</body>
</html>