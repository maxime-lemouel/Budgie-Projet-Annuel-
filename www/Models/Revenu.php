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

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT r.*, c.nom AS compte_nom, dr.ponctuelle, dr.iteration
             FROM public.revenus r
             LEFT JOIN public.compte c ON c.id = r.compte_id
             LEFT JOIN public.duree_revenus dr ON dr.revenus_id = r.id
             ORDER BY r.date_debut DESC'
        );
        return $stmt->fetchAll();
    }

    public function findAllByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, c.nom AS compte_nom, dr.ponctuelle, dr.iteration
             FROM public.revenus r
             LEFT JOIN public.compte c ON c.id = r.compte_id
             LEFT JOIN public.duree_revenus dr ON dr.revenus_id = r.id
             WHERE c.user_id = :user_id
             ORDER BY r.date_debut DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function belongsToUser(int $revenuId, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1
             FROM public.revenus r
             INNER JOIN public.compte c ON c.id = r.compte_id
             WHERE r.id = :revenu_id AND c.user_id = :user_id'
        );
        $stmt->execute(['revenu_id' => $revenuId, 'user_id' => $userId]);
        return (bool) $stmt->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, dr.ponctuelle, dr.iteration
             FROM public.revenus r
             LEFT JOIN public.duree_revenus dr ON dr.revenus_id = r.id
             WHERE r.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByCompte(int $compteId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, dr.ponctuelle, dr.iteration
             FROM public.revenus r
             LEFT JOIN public.duree_revenus dr ON dr.revenus_id = r.id
             WHERE r.compte_id = :compte_id
             ORDER BY r.date_debut DESC'
        );
        $stmt->execute(['compte_id' => $compteId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int|false
    {
        try {
            $this->pdo->beginTransaction();

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

            $revenuId = (int) $stmt->fetchColumn();

            $sqlDuree = 'INSERT INTO public.duree_revenus (revenus_id, ponctuelle, iteration)
                         VALUES (:revenus_id, :ponctuelle, :iteration)';

            $stmtDuree = $this->pdo->prepare($sqlDuree);
            $stmtDuree->bindValue(':revenus_id', $revenuId,           \PDO::PARAM_INT);
            $stmtDuree->bindValue(':ponctuelle', $data['ponctuelle'],  \PDO::PARAM_BOOL);
            $stmtDuree->bindValue(':iteration',  $data['iteration'] ?: null);
            $stmtDuree->execute();

            $this->pdo->commit();
            return $revenuId;

        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur création revenu : " . $e->getMessage());
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->pdo->beginTransaction();

            $sql = 'UPDATE public.revenus SET
                        compte_id    = :compte_id,
                        nom          = :nom,
                        description  = :description,
                        date_debut   = :date_debut,
                        date_fin     = :date_fin,
                        montant      = :montant,
                        date_updated = CURRENT_DATE
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':compte_id',   $data['compte_id'],  \PDO::PARAM_INT);
            $stmt->bindValue(':nom',         $data['nom']);
            $stmt->bindValue(':description', $data['description'] ?? '');
            $stmt->bindValue(':date_debut',  $data['date_debut']);
            $stmt->bindValue(':date_fin',    $data['date_fin'] ?: null);
            $stmt->bindValue(':montant',     $data['montant']);
            $stmt->bindValue(':id',          $id,                 \PDO::PARAM_INT);
            $stmt->execute();

            $sqlDuree = 'UPDATE public.duree_revenus SET
                            ponctuelle = :ponctuelle,
                            iteration  = :iteration
                         WHERE revenus_id = :revenus_id';

            $stmtDuree = $this->pdo->prepare($sqlDuree);
            $stmtDuree->bindValue(':ponctuelle', $data['ponctuelle'], \PDO::PARAM_BOOL);
            $stmtDuree->bindValue(':iteration',  $data['iteration'] ?: null);
            $stmtDuree->bindValue(':revenus_id', $id,                \PDO::PARAM_INT);
            $stmtDuree->execute();

            $this->pdo->commit();
            return true;

        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur modification revenu : " . $e->getMessage());
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