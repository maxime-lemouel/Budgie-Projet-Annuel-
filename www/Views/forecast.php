<?php
$accounts = isset($accounts)
        ? json_decode($accounts, true)
        : [];

$datePrevision =
        $datePrevision
        ?? date('Y-m-d');

function formatDateFr(?string $date): string {
    if (!$date) return '—';
    $d = DateTime::createFromFormat('Y-m-d', substr($date, 0, 10));
    return $d ? htmlspecialchars($d->format('d/m/Y')) : htmlspecialchars($date);
}
?>

    <div class="page-heading">

        <div class="page-heading__text">

            <h1 class="page-heading__title">
                Prévisions
            </h1>

            <p class="page-heading__subtitle">
                Consultez l'évolution prévisionnelle de vos comptes
            </p>

        </div>

        <form method="GET" class="form-row">

            <div class="form-group">

                <label class="form-label" for="date_prevision">
                    Date de prévision
                </label>

                <input
                        class="form-control"
                        type="date"
                        id="date_prevision"
                        name="date_prevision"
                        value="<?= htmlspecialchars($datePrevision) ?>"
                        min="<?= date('Y-m-d') ?>"
                >

            </div>

            <button class="button button--primary" type="submit">
                Calculer
            </button>

        </form>

    </div>

<?php if (empty($accounts)): ?>

    <div class="card">
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
            </svg>
            <div class="empty-state__title">Aucun compte</div>
            <div class="empty-state__desc">
                Ajoutez un compte pour afficher des prévisions.
            </div>
        </div>
    </div>

<?php else: ?>

    <div class="stats-grid">

        <?php foreach ($accounts as $account): ?>

            <?php
            $type = $account['type'] ?? '';

            $badgeClass = match ($type) {
                    'livret_a'       => 'badge--green',
                    'compte_courant' => 'badge--blue',
                    default          => 'badge--grey',
            };

            $iconClass = match ($type) {
                    'livret_a'       => 'stat-card__icon--green',
                    'compte_courant' => 'stat-card__icon--blue',
                    default          => 'stat-card__icon--grey',
            };

            $typeLabel =
                    match ($type) {
                        'livret_a'       => 'Livret A',
                        'compte_courant' => 'Compte courant',
                        default          => ucfirst($type)
                    };

            $solde = (float)(
                    $account['solde_previsionnel']
                    ?? 0
            );

            $soldeClass = $solde >= 0
                    ? 'stat-card__value--positive'
                    : 'stat-card__value--negative';
            ?>

            <div class="stat-card">

                <div class="stat-card__icon <?= $iconClass ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                </div>

                <div class="stat-card__name">
                    <?= htmlspecialchars($account['nom'] ?? '') ?>
                </div>

                <span class="badge <?= $badgeClass ?>">
                    <?= htmlspecialchars($typeLabel) ?>
                </span>

                <?php if (!empty($account['description'])): ?>
                    <div class="stat-card__description">
                        <?= htmlspecialchars($account['description']) ?>
                    </div>
                <?php endif; ?>

                <hr class="stat-card__divider">

                <div class="stat-card__rates">

                    <div class="stat-card__rate">
                        <div class="stat-card__label">Taux rémun.</div>
                        <div class="stat-card__rate-value">
                            <?= number_format((float)($account['taux_remuneration'] ?? 0), 2) ?>&nbsp;%
                        </div>
                    </div>

                    <div class="stat-card__rate">
                        <div class="stat-card__label">Taux impos.</div>
                        <div class="stat-card__rate-value">
                            <?= number_format((float)($account['taux_imposition'] ?? 0), 2) ?>&nbsp;%
                        </div>
                    </div>

                </div>

                <hr class="stat-card__divider">

                <div class="stat-card__label">
                    Solde prévisionnel au <?= formatDateFr($datePrevision) ?>
                </div>

                <div class="stat-card__value <?= $soldeClass ?>">
                    <?= number_format($solde, 2, ',', ' ') ?>&nbsp;€
                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>