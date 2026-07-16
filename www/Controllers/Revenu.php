<?php

namespace App\Controllers;

use App\Core\Render;
use App\Models\Revenu as RevenuModel;
use App\Models\Compte as CompteModel;

class Revenu
{
    private RevenuModel $revenuModel;
    private CompteModel $compteModel;

    public function __construct()
    {
        $this->revenuModel = new RevenuModel();
        $this->compteModel = new CompteModel();
    }

    public function list(): void
    {
        Auth::requireAuth();

        $revenus = $this->revenuModel->findAllByUser($_SESSION['user_id']);
        $accounts = $this->compteModel->findAll($_SESSION['user_id']);

        $render = new Render("revenu", "frontoffice");
        $render->assign("revenus", json_encode($revenus));
        $render->assign("accounts", json_encode($accounts));
        $render->assign("errors",  json_encode([]));
        $render->render();
    }

    public function create(): void
    {
        Auth::requireAuth();

        $errors   = [];
        $accounts = $this->compteModel->findAll($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ponctuelle = ($_POST['frequence'] ?? '') === 'ponctuelle';

            $iteration = (!$ponctuelle && isset($_POST['iteration']) && $_POST['iteration'] !== '')
                         ? (int) $_POST['iteration'] : null;

            $data = [
                'compte_id'   => isset($_POST['compte_id']) && $_POST['compte_id'] !== ''
                                  ? (int) $_POST['compte_id'] : null,
                'nom'         => trim($_POST['nom']         ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'date_debut'  => trim($_POST['date_debut']  ?? ''),
                'date_fin'    => trim($_POST['date_fin']    ?? '') ?: null,
                'montant'     => isset($_POST['montant']) && $_POST['montant'] !== ''
                                  ? (float) $_POST['montant'] : null,
                'ponctuelle'  => $ponctuelle,
                'iteration'   => $iteration,
            ];

            if (empty($data['compte_id']))
                $errors[] = 'Le compte est obligatoire.';
            if (empty($data['nom']))
                $errors[] = 'Le nom est obligatoire.';
            if ($data['montant'] === null)
                $errors[] = 'Le montant est obligatoire.';
            if ($data['montant'] !== null && $data['montant'] <= 0)
                $errors[] = 'Le montant doit être positif.';
            if (empty($data['date_debut']))
                $errors[] = 'La date de début est obligatoire.';
            if (!empty($data['date_fin']) && !empty($data['date_debut']) && $data['date_fin'] < $data['date_debut'])
                $errors[] = 'La date de fin ne peut pas être antérieure à la date de début.';
            if (!$ponctuelle && ($iteration === null || $iteration < 1))
                $errors[] = 'Le nombre de mois doit être supérieur à 0.';

            if (!empty($data['compte_id'])) {
                $comptesDeLUtilisateur = array_column($accounts, 'id');
                if (!in_array($data['compte_id'], $comptesDeLUtilisateur, true)) {
                    $errors[] = 'Le compte sélectionné est invalide.';
                }
            }

            if (empty($errors)) {
                $id = $this->revenuModel->create($data);

                if ($id !== false) {
                    header('Location: /revenues');
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la création du revenu.';
                }
            }
        }

        $render = new Render("revenu", "frontoffice");
        $render->assign("accounts", json_encode($accounts));
        $render->assign("errors",   json_encode($errors));
        $render->render();
    }

    public function edit(): void
    {
        Auth::requireAuth();

        $id     = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $userId = $_SESSION['user_id'];

        if (!$id) {
            header('Location: /revenues');
            exit;
        }

        if (!$this->revenuModel->belongsToUser($id, $userId)) {
            header('Location: /revenues');
            exit;
        }

        $revenu   = $this->revenuModel->findById($id);
        $accounts = $this->compteModel->findAll($userId);
        $errors   = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ponctuelle = ($_POST['frequence'] ?? '') === 'ponctuelle';
            $iteration  = (!$ponctuelle && isset($_POST['iteration']) && $_POST['iteration'] !== '')
                          ? (int) $_POST['iteration'] : null;

            $data = [
                'compte_id'   => isset($_POST['compte_id']) && $_POST['compte_id'] !== ''
                                  ? (int) $_POST['compte_id'] : null,
                'nom'         => trim($_POST['nom']         ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'date_debut'  => trim($_POST['date_debut']  ?? ''),
                'date_fin'    => trim($_POST['date_fin']    ?? '') ?: null,
                'montant'     => isset($_POST['montant']) && $_POST['montant'] !== ''
                                  ? (float) $_POST['montant'] : null,
                'ponctuelle'  => $ponctuelle,
                'iteration'   => $iteration,
            ];

            if (empty($data['compte_id'])) {
                $errors[] = 'Le compte est obligatoire.';
            } elseif (!$this->compteModel->findByIdAndUser($data['compte_id'], $userId)) {
                $errors[] = 'Le compte sélectionné est invalide.';
            }
            if (empty($data['nom']))
                $errors[] = 'Le nom est obligatoire.';
            if ($data['montant'] === null)
                $errors[] = 'Le montant est obligatoire.';
            if ($data['montant'] !== null && $data['montant'] <= 0)
                $errors[] = 'Le montant doit être positif.';
            if (empty($data['date_debut']))
                $errors[] = 'La date de début est obligatoire.';
            if (!empty($data['date_fin']) && !empty($data['date_debut']) && $data['date_fin'] < $data['date_debut'])
                $errors[] = 'La date de fin ne peut pas être antérieure à la date de début.';
            if (!$ponctuelle && ($iteration === null || $iteration < 1))
                $errors[] = 'Le nombre de mois doit être supérieur à 0.';

            if (empty($errors)) {
                $updated = $this->revenuModel->update($id, $data);

                if ($updated) {
                    header('Location: /revenues');
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la modification du revenu.';
                }
            }

            $revenu = array_merge($revenu, $data);
        }

        $render = new Render("revenu", "frontoffice");
        $render->assign("revenu",   json_encode($revenu));
        $render->assign("accounts", json_encode($accounts));
        $render->assign("errors",   json_encode($errors));
        $render->render();
    }

    public function delete(): void
    {
        Auth::requireAuth();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if (!$id) {
            header('Location: /revenues');
            exit;
        }

        if (!$this->revenuModel->belongsToUser($id, $_SESSION['user_id'])) {
            http_response_code(403);
            header('Location: /revenues');
            exit;
        }

        $this->revenuModel->delete($id);
        header('Location: /revenues');
        exit;
    }
}