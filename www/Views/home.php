<?php
$pseudo = htmlspecialchars($pseudo ?? 'Utilisateur');
$email = htmlspecialchars($email ?? '');
?>

<div class="page-heading">
    <div class="page-heading__text">
        <h1 class="page-heading__title">Bonjour, <?= $pseudo ?></h1>
        <p class="page-heading__subtitle">Bienvenue sur votre tableau de bord Budgie</p>
    </div>
</div>

<div class="welcome-banner">
    <div>
        <div class="welcome-banner__title">Gérez vos finances sereinement</div>
        <div class="welcome-banner__sub">Suivez vos dépenses, comptes et budgets en un seul endroit.</div>
    </div>
    <a href="/forecast" class="button button--ghost" style="color:white; border: 1px solid rgba(255,255,255,0.3);">
        Faire une prévision
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5m0 0h-6m6 0a2 2 0 01-2 2h-4"/>
            </svg>
        </div>
        <div class="stat-card__label">Comptes</div>
        <div class="stat-card__value">
            <a href="/accounts" style="color:inherit; text-decoration:none;">Gérer →</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--green">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5V4.5m0 0l-5 5m5-5l5 5"/>
            </svg>
        </div>
        <div class="stat-card__label">Revenues</div>
        <div class="stat-card__value">
            <a href="/revenues" style="color:inherit; text-decoration:none;">Voir →</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--red">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
            </svg>
        </div>
        <div class="stat-card__label">Dépenses</div>
        <div class="stat-card__value">
            <a href="/expenses" style="color:inherit; text-decoration:none;">Voir →</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--grey">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="stat-card__label">Contact</div>
        <div class="stat-card__value">
            <a href="/contact" style="color:inherit; text-decoration:none;">Écrire →</a>
        </div>
    </div>
</div>