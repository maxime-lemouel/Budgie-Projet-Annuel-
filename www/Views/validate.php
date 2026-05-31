<?php
$success = ($success ?? 'false') === 'true';
$message = $message ?? '';
?>

<div class="card card--auth card--auth-sm">

    <div class="logo-wrapper logo-wrapper--sm">
        <?php if ($success): ?>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        <?php else: ?>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
        <?php endif; ?>
    </div>

    <div>
        <h1 class="page-title"><?= $success ? 'Compte activé !' : 'Lien invalide' ?></h1>
        <?php if ($message): ?>
            <p class="page-description"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>

    <?php if ($success): ?>
        <div class="alert alert--success">Votre compte est actif. Vous pouvez vous connecter.</div>
        <a href="/login" class="button button--full">Se connecter</a>
    <?php else: ?>
        <div class="alert alert--error">Ce lien est invalide ou a expiré.</div>
        <div class="footer-links">
            <a href="/register" class="link link--blue">← Créer un nouveau compte</a>
        </div>
    <?php endif; ?>

</div>