<?php

namespace App\Controllers;

use App\Core\Render;
use App\Models\Depense as DepenseModel;
use App\Models\Revenu as RevenuModel;
use App\Models\Compte as CompteModel;

class Forecast
{
    private DepenseModel $depenseModel;
    private RevenuModel $revenuModel;
    private CompteModel $compteModel;

    public function __construct()
    {
        $this->depenseModel = new DepenseModel();
        $this->revenuModel  = new RevenuModel();
        $this->compteModel  = new CompteModel();
    }

    public function list(): void
    {
        Auth::requireAuth();

        $accounts = $this->compteModel->findAll(
            $_SESSION['user_id']
        );

        // =====================================================
        // Date de prévision
        // =====================================================

        $datePrevision =
            $_GET['date_prevision']
            ?? date('Y-m-d');

        $dateCible = new \DateTimeImmutable(
            $datePrevision
        );

        // =====================================================
        // Calcul des prévisions
        // =====================================================

        foreach ($accounts as &$account) {

            $revenus = $this->revenuModel
                ->findByCompte($account['id']);

            $depenses = $this->depenseModel
                ->findByCompte($account['id']);

            $tauxRemun = (float)(
                $account['taux_remuneration']
                ?? 0
            );

            $tauxImpos = (float)(
                $account['taux_imposition']
                ?? 0
            );

            $tauxJournalier = $tauxRemun > 0
                ? (1 + $tauxRemun / 100) ** (1 / 365) - 1
                : 0.0;

            $solde = 0.0;

            $dateCourante = new \DateTimeImmutable(
                $account['date_creation']
            );

            // =================================================
            // Boucle jour par jour
            // =================================================

            while ($dateCourante <= $dateCible) {

                // =============================================
                // 1. Intérêts
                // =============================================

                // uniquement si solde positif
                if (
                    $tauxJournalier > 0
                    && $solde > 0
                ) {

                    $interetsBruts =
                        $solde * $tauxJournalier;

                    $interetsNets =
                        $interetsBruts *
                        (1 - $tauxImpos / 100);

                    $solde += $interetsNets;
                }

                // =============================================
                // 2. Revenus
                // =============================================

                foreach ($revenus as $revenu) {

                    $montant = (float)(
                    $revenu['montant']
                    );

                    $dateDebut =
                        new \DateTimeImmutable(
                            $revenu['date_debut']
                        );

                    $dateFin =
                        !empty($revenu['date_fin'])
                            ? new \DateTimeImmutable(
                            $revenu['date_fin']
                        )
                            : null;

                    $ponctuelle = (bool)(
                    $revenu['ponctuelle']
                    );

                    // -----------------------------------------
                    // Ponctuel
                    // -----------------------------------------

                    if ($ponctuelle) {

                        if (
                            $dateCourante->format('Y-m-d')
                            === $dateDebut->format('Y-m-d')
                        ) {
                            $solde += $montant;
                        }

                        continue;
                    }

                    // -----------------------------------------
                    // Récurrent
                    // -----------------------------------------

                    if ($dateCourante < $dateDebut) {
                        continue;
                    }

                    if (
                        $dateFin !== null
                        && $dateCourante > $dateFin
                    ) {
                        continue;
                    }

                    $iteration = (int)(
                        $revenu['iteration']
                        ?? 1
                    );

                    $nbMois = ((int)$dateCourante->format('Y') - (int)$dateDebut->format('Y')) * 12 + ((int)$dateCourante->format('m') - (int)$dateDebut->format('m'));

                    $jourDebut = (int)$dateDebut->format('d');
                    $dernierJourMois = (int)$dateCourante->format('t');
                    $jourCible = min($jourDebut, $dernierJourMois);

                    if (
                        $nbMois >= 0
                        && $nbMois % $iteration === 0
                        && (int)$dateCourante->format('d') === $jourCible
                    ) {
                        $solde += $montant;
                    }
                }

                // =============================================
                // 3. Dépenses
                // =============================================

                foreach ($depenses as $depense) {

                    $montant = (float)(
                    $depense['montant']
                    );

                    $dateDebut =
                        new \DateTimeImmutable(
                            $depense['date_debut']
                        );

                    $dateFin =
                        !empty($depense['date_fin'])
                            ? new \DateTimeImmutable(
                            $depense['date_fin']
                        )
                            : null;

                    $ponctuelle = (bool)(
                    $depense['ponctuelle']
                    );

                    // -----------------------------------------
                    // Ponctuelle
                    // -----------------------------------------

                    if ($ponctuelle) {

                        if (
                            $dateCourante->format('Y-m-d')
                            === $dateDebut->format('Y-m-d')
                        ) {
                            $solde -= $montant;
                        }

                        continue;
                    }

                    // -----------------------------------------
                    // Récurrente
                    // -----------------------------------------

                    if ($dateCourante < $dateDebut) {
                        continue;
                    }

                    if (
                        $dateFin !== null
                        && $dateCourante > $dateFin
                    ) {
                        continue;
                    }

                    $iteration = (int)(
                        $depense['iteration']
                        ?? 1
                    );

                    $nbMois = ((int)$dateCourante->format('Y') - (int)$dateDebut->format('Y')) * 12
                        + ((int)$dateCourante->format('m') - (int)$dateDebut->format('m'));

                    $jourDebut = (int)$dateDebut->format('d');
                    $dernierJourMois = (int)$dateCourante->format('t'); // 't' = nb de jours dans le mois courant
                    $jourCible = min($jourDebut, $dernierJourMois);

                    if (
                        $nbMois >= 0
                        && $nbMois % $iteration === 0
                        && (int)$dateCourante->format('d') === $jourCible
                    ) {
                        $solde -= $montant;
                    }
                }

                // =============================================
                // Jour suivant
                // =============================================

                $dateCourante =
                    $dateCourante->modify('+1 day');
            }

            $account['solde_previsionnel'] =
                round($solde, 2);
        }

        unset($account);

        // =====================================================
        // Vue
        // =====================================================

        $render = new Render(
            "forecast",
            "frontoffice"
        );

        $render->assign(
            "accounts",
            json_encode($accounts)
        );

        $render->assign(
            "datePrevision",
            $datePrevision
        );

        $render->render();
    }
}