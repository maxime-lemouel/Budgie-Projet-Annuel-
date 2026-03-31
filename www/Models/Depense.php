<?php

namespace App\Models;

use App\Core\Database;

class Depense
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT d.*, c.nom AS compte_nom, dd.ponctuelle, dd.iteration
             FROM public.depense d
             LEFT JOIN public.compte c ON c.id = d.compte_id
             LEFT JOIN public.duree_depense dd ON dd.depense_id = d.id
             ORDER BY d.date_debut DESC'
        );
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT d.*, dd.ponctuelle, dd.iteration
             FROM public.depense d
             LEFT JOIN public.duree_depense dd ON dd.depense_id = d.id
             WHERE d.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByCompte(int $compteId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT d.*, dd.ponctuelle, dd.iteration
             FROM public.depense d
             LEFT JOIN public.duree_depense dd ON dd.depense_id = d.id
             WHERE d.compte_id = :compte_id
             ORDER BY d.date_debut DESC'
        );
        $stmt->execute(['compte_id' => $compteId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int|false
    {
        try {
            $this->pdo->beginTransaction();

            $sql = 'INSERT INTO public.depense
                        (compte_id, nom, description, date_debut, date_fin, montant)
                    VALUES
                        (:compte_id, :nom, :description, :date_debut, :date_fin, :montant)
                    RETURNING id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':compte_id',   $data['compte_id'],  \PDO::PARAM_INT);
            $stmt->bindValue(':nom',         $data['nom']);
            $stmt->bindValue(':description', $data['description'] ?? '');
            $stmt->bindValue(':date_debut',  $data['date_debut']);
            $stmt->bindValue(':date_fin',    $data['date_fin'] ?: null);
            $stmt->bindValue(':montant',     $data['montant']);
            $stmt->execute();

            $depenseId = (int) $stmt->fetchColumn();

            $sqlDuree = 'INSERT INTO public.duree_depense (depense_id, ponctuelle, iteration)
                         VALUES (:depense_id, :ponctuelle, :iteration)';

            $stmtDuree = $this->pdo->prepare($sqlDuree);
            $stmtDuree->bindValue(':depense_id', $depenseId,          \PDO::PARAM_INT);
            $stmtDuree->bindValue(':ponctuelle', $data['ponctuelle'],  \PDO::PARAM_BOOL);
            $stmtDuree->bindValue(':iteration',  $data['iteration'] ?: null);
            $stmtDuree->execute();

            $this->pdo->commit();
            return $depenseId;

        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur création dépense : " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM public.depense WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            error_log("Erreur suppression dépense : " . $e->getMessage());
            return false;
        }
    }
}
