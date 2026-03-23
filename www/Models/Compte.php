<?php

namespace App\Models;

use App\Core\Database;

class Compte
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM public.compte ORDER BY date_creation DESC');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM public.compte WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int|false
    {
        try {
            $sql = 'INSERT INTO public.compte 
                    (user_id, nom, description, type, taux_remuneration, taux_imposition) 
                    VALUES (:user_id, :nom, :description, :type, :taux_remuneration, :taux_imposition) 
                    RETURNING id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id',            $data['user_id'], \PDO::PARAM_INT);
            $stmt->bindValue(':nom',                $data['nom']);
            $stmt->bindValue(':description',        $data['description'] ?? '');
            $stmt->bindValue(':type',               $data['type']);
            $stmt->bindValue(':taux_remuneration',  $data['taux_remuneration']);
            $stmt->bindValue(':taux_imposition',    $data['taux_imposition']);

            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log("Erreur création compte: " . $e->getMessage());
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $sql = 'UPDATE public.compte SET
                        nom = :nom,
                        description = :description,
                        type = :type,
                        taux_remuneration = :taux_remuneration,
                        taux_imposition = :taux_imposition,
                        date_updated = CURRENT_DATE
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nom',                $data['nom']);
            $stmt->bindValue(':description',        $data['description'] ?? '');
            $stmt->bindValue(':type',               $data['type']);
            $stmt->bindValue(':taux_remuneration',  $data['taux_remuneration']);
            $stmt->bindValue(':taux_imposition',    $data['taux_imposition']);
            $stmt->bindValue(':id',                 $id, \PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erreur update compte: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM public.compte WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            error_log("Erreur delete compte: " . $e->getMessage());
            return false;
        }
    }
}