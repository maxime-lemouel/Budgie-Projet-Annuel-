<?php

namespace App\Controllers;

use App\Core\Render;
use App\Models\Compte as CompteModel;

class Compte
{
    private CompteModel $compteModel;

    public function __construct()
    {
        $this->compteModel = new CompteModel();
    }

    public function list(): void
    {
        Auth::requireAuth();

        $accounts = $this->compteModel->findAll();

        $render = new Render("compte", "frontoffice");
        $render->assign("accounts", json_encode($accounts));
        $render->render();
    }

    public function create(): void
    {
        Auth::requireAuth();

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'nom'               => trim($_POST['nom'] ?? ''),
                'description'       => trim($_POST['description'] ?? ''),
                'type'              => trim($_POST['type'] ?? ''),
                'taux_remuneration' => $_POST['taux_remuneration'] !== '' ? (float) $_POST['taux_remuneration'] : null,
                'taux_imposition'   => $_POST['taux_imposition'] !== '' ? (float) $_POST['taux_imposition'] : null,
                'user_id'           => $_SESSION['user_id'] ?? 1,
            ];

            // Validation
            if (empty($data['nom']))  $errors[] = 'Le nom est obligatoire.';
            if (empty($data['type'])) $errors[] = 'Le type de compte est obligatoire.';

            if (empty($errors)) {
                $id = $this->compteModel->create($data);

                if ($id !== false) {
                    header('Location: /accounts');
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la création du compte.';
                }
            }
        }

        $render = new Render("compte", "frontoffice");
        $render->assign("errors", json_encode($errors));
        $render->render();
    }

    public function edit(): void
    {
        Auth::requireAuth();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if (!$id) {
            header('Location: /accounts');
            exit;
        }

        $account = $this->compteModel->findById($id);

        if (!$account) {
            header('Location: /accounts');
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'nom'               => trim($_POST['nom'] ?? ''),
                'description'       => trim($_POST['description'] ?? ''),
                'type'              => trim($_POST['type'] ?? ''),
                'taux_remuneration' => $_POST['taux_remuneration'] !== '' ? (float) $_POST['taux_remuneration'] : null,
                'taux_imposition'   => $_POST['taux_imposition'] !== '' ? (float) $_POST['taux_imposition'] : null,
                'user_id'           => $_SESSION['user_id'] ?? 1,
            ];

            // Validation
            if (empty($data['nom']))  $errors[] = 'Le nom est obligatoire.';
            if (empty($data['type'])) $errors[] = 'Le type de compte est obligatoire.';

            if (empty($errors)) {
                $updated = $this->compteModel->update($id, $data);

                if ($updated) {
                    header('Location: /accounts');
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la mise à jour du compte.';
                }
            }
        }

        $render = new Render("compte", "frontoffice");
        $render->assign("account", json_encode($account));
        $render->assign("errors", json_encode($errors));
        $render->render();
    }

    public function delete(): void
    {
        Auth::requireAuth();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if (!$id) {
            header('Location: /accounts');
            exit;
        }

        $account = $this->compteModel->findById($id);

        if (!$account) {
            header('Location: /accounts');
            exit;
        }

        $this->compteModel->delete($id);

        header('Location: /accounts');
        exit;
    }
}