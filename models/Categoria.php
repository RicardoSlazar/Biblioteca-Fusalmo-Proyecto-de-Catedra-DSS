<?php
/**
 * Modelo Categoria — Biblioteca Fusalmo
 * CRUD completo para categorías de libros
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';

class Categoria {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene todas las categorías.
     */
    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT id, nombre, descripcion, created_at
             FROM categorias
             ORDER BY nombre"
        );
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una categoría por ID.
     */
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT id, nombre, descripcion, created_at
             FROM categorias
             WHERE id = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Verifica si una categoría existe por nombre.
     */
    public function existsByName(string $nombre, int $excludeId = 0): bool {
        $sql = "SELECT COUNT(*) FROM categorias WHERE nombre = :nombre";
        if ($excludeId > 0) $sql .= " AND id != :id";
        
        $stmt = $this->db->prepare($sql);
        $params = [':nombre' => $nombre];
        if ($excludeId > 0) $params[':id'] = $excludeId;
        
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Crea una nueva categoría.
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO categorias (nombre, descripcion, created_at)
             VALUES (:nombre, :descripcion, NOW())"
        );
        return $stmt->execute([
            ':nombre'       => Security::sanitizeString($data['nombre'] ?? ''),
            ':descripcion'  => Security::sanitizeString($data['descripcion'] ?? ''),
        ]);
    }

    /**
     * Actualiza una categoría.
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE categorias 
             SET nombre = :nombre,
                 descripcion = :descripcion
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'           => $id,
            ':nombre'       => Security::sanitizeString($data['nombre'] ?? ''),
            ':descripcion'  => Security::sanitizeString($data['descripcion'] ?? ''),
        ]);
    }

    /**
     * Elimina una categoría.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Obtiene cantidad de libros por categoría.
     */
    public function getLibrosCount(int $id): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM libros WHERE categoria_id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }
}
