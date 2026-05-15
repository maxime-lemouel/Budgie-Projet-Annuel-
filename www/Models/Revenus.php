<?php

namespace App\Models;

use App\Core\Database;

class Revenu
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findAll(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, c.nom AS compte_nom
             FROM public.revenus r
             JOIN public.compte c ON c.id = r.compte_id
             WHERE c.user_id = :user_id
             ORDER BY r.date_debut DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, c.nom AS compte_nom
             FROM public.revenus r
             JOIN public.compte c ON c.id = r.compte_id
             WHERE r.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function belongsToUser(int $revenuId, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
             FROM public.revenus r
             JOIN public.compte c ON c.id = r.compte_id
             WHERE r.id = :revenu_id AND c.user_id = :user_id'
        );
        $stmt->execute(['revenu_id' => $revenuId, 'user_id' => $userId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function findByCompte(int $compteId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM public.revenus
             WHERE compte_id = :compte_id
             ORDER BY date_debut DESC'
        );
        $stmt->execute(['compte_id' => $compteId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int|false
    {
        try {
            $sql = 'INSERT INTO public.revenus
                        (compte_id, nom, description, date_debut, date_fin, montant, date_updated)
                    VALUES
                        (:compte_id, :nom, :description, :date_debut, :date_fin, :montant, CURRENT_DATE)
                    RETURNING id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':compte_id',   $data['compte_id'],  \PDO::PARAM_INT);
            $stmt->bindValue(':nom',         $data['nom']);
            $stmt->bindValue(':description', $data['description'] ?? '');
            $stmt->bindValue(':date_debut',  $data['date_debut']);
            $stmt->bindValue(':date_fin',    $data['date_fin'] ?: null);
            $stmt->bindValue(':montant',     $data['montant']);
            $stmt->execute();

            return (int) $stmt->fetchColumn();

        } catch (\PDOException $e) {
            error_log("Erreur création revenu : " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM public.revenus WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            error_log("Erreur suppression revenu : " . $e->getMessage());
            return false;
        }
    }
}