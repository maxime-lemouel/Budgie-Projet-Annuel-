<?php

namespace App\Controllers;

use App\Core\Render;
use App\Models\Depense as DepenseModel;
use App\Models\Compte as CompteModel;

class Depense
{
    private DepenseModel $depenseModel;
    private CompteModel  $compteModel;

    public function __construct()
    {
        $this->depenseModel = new DepenseModel();
        $this->compteModel  = new CompteModel();
    }

    public function list(): void
    {
        Auth::requireAuth();

        $depenses = $this->depenseModel->findAllByUser($_SESSION['user_id']);
        $accounts = $this->compteModel->findAll($_SESSION['user_id']);

        $render = new Render("depense", "frontoffice");
        $render->assign("depenses", json_encode($depenses));
        $render->assign("accounts", json_encode($accounts));
        $render->assign("errors",   json_encode([]));
        $render->render();
    }

    public function create(): void
    {
        Auth::requireAuth();

        $errors   = [];
        $accounts = $this->compteModel->findAll($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ponctuelle = true si le select vaut 'ponctuelle', false si 'mois'
            $ponctuelle = ($_POST['frequence'] ?? '') === 'ponctuelle';

            // iteration = N uniquement si récurrente
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

            // Validation
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

            if (empty($errors)) {
                $id = $this->depenseModel->create($data);

                if ($id !== false) {
                    header('Location: /expenses');
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la création de la dépense.';
                }
            }
        }

        $render = new Render("depense", "frontoffice");
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
        header('Location: /expenses');
        exit;
    }

    // Requête intermédiaire de sécurité : vérifie que la dépense
    // appartient bien à un compte de l'utilisateur connecté.
    if (!$this->depenseModel->belongsToUser($id, $userId)) {
        header('Location: /expenses');
        exit;
    }

    $depense  = $this->depenseModel->findById($id);
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

        // Validation
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
            $updated = $this->depenseModel->update($id, $data);

            if ($updated) {
                header('Location: /expenses');
                exit;
            } else {
                $errors[] = 'Erreur lors de la modification de la dépense.';
            }
        }

        // En cas d'erreur, on remet les valeurs postées dans $depense
        $depense = array_merge($depense, $data);
    }

    $render = new Render("depense", "frontoffice");
    $render->assign("depense",  json_encode($depense));
    $render->assign("accounts", json_encode($accounts));
    $render->assign("errors",   json_encode($errors));
    $render->render();
}


    public function delete(): void
    {
        Auth::requireAuth();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if (!$id) {
            header('Location: /expenses');
            exit;
        }

        // Vérifie que la dépense appartient bien à l'utilisateur connecté
        if (!$this->depenseModel->belongsToUser($id, $_SESSION['user_id'])) {
            http_response_code(403);
            header('Location: /expenses');
            exit;
        }

        $this->depenseModel->delete($id);
        header('Location: /expenses');
        exit;
    }
}
