<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
    <link rel="stylesheet" href="dist/css/reset.css">
</head>
<body>

<?php
$errors  = json_decode($errors ?? '[]', true);
$success = ($success ?? 'false') == 'true';
$token   = $token ?? '';
?>

<div class="card card--auth card--auth-sm">

    <div>
        <h1 class="page-title">Nouveau mot de passe</h1>
    </div>

    <?php if ($success) : ?>

        <div class="alert alert--success">Mot de passe réinitialisé avec succès !</div>
        <a href="/login" class="link link--blue">Se connecter</a>

    <?php else : ?>

        <?php if (!empty($errors)) :
            foreach ($errors as $error) :?>

                <div class="alert alert--error"><?php print_r($error); ?></div>
            <?php endforeach;
        endif; ?>

        <?php if (empty($token)) : ?>

            <div class="alert alert--error">Token manquant ou invalide.</div>
            <a href="/forgot-password" class="link link--blue">← Demander un nouveau lien</a>

        <?php else : ?>

            <p class="page-description">Choisissez un mot de passe fort d'au moins 8 caractères.</p>

            <form class="form" method="post">

                <div class="form-group">
                    <label class="form-label" for="pwd">Nouveau mot de passe</label>
                    <input class="input" type="password" id="pwd" name="pwd" required minlength="8">
                </div>

                <div class="form-group">
                    <label class="form-label" for="pwdConfirm">Confirmer le mot de passe</label>
                    <input class="input" type="password" id="pwdConfirm" name="pwdConfirm" required minlength="8">
                </div>

                <button class="button button--full" type="submit">Réinitialiser</button>

            </form>

            <a href="/login" class="link link--blue">← Retour à la connexion</a>

        <?php endif; ?>

    <?php endif; ?>

</div>

</body>
</html>