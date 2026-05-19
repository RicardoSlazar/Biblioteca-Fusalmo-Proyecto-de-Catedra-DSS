<?php
/**
 * Modelo Usuario — Biblioteca Fusalmo
 * Todas las consultas usan Prepared Statements (previene SQL Injection)
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Busca un usuario por correo o nombre de usuario (login).
     * Uso de Prepared Statement para evitar SQL Injection.
     */
    public function findByCredential(string $credential): array|false {
        $stmt = $this->db->prepare(
            "SELECT id, nombre, correo, contrasena, rol, estado
             FROM usuarios
             WHERE (correo = :credential OR nombre = :credential)
               AND estado = 'activo'
             LIMIT 1"
        );
        $stmt->execute([':credential' => $credential]);
        return $stmt->fetch();
    }

    /**
     * Registra un nuevo usuario.
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nombre, correo, contrasena, telefono, rol, estado, created_at)
             VALUES (:nombre, :correo, :contrasena, :telefono, :rol, 'activo', NOW())"
        );
        return $stmt->execute([
            ':nombre'    => Security::sanitizeString($data['nombre']),
            ':correo'    => Security::sanitizeEmail($data['correo']),
            ':contrasena'=> Security::hashPassword($data['contrasena']),
            ':telefono'  => Security::sanitizeString($data['telefono'] ?? ''),
            ':rol'       => $data['rol'] ?? 'usuario',
        ]);
    }

    /**
     * Verifica si el correo ya está registrado.
     */
    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM usuarios WHERE correo = :email"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Obtiene todos los usuarios (solo admin).
     */
    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT id, nombre, correo, rol, estado, created_at FROM usuarios ORDER BY nombre"
        );
        return $stmt->fetchAll();
    }

    /**
     * Actualiza el estado de un usuario.
     */
    public function updateStatus(int $id, string $estado): bool {
        $allowed = ['activo', 'inactivo'];
        if (!in_array($estado, $allowed)) return false;

        $stmt = $this->db->prepare(
            "UPDATE usuarios SET estado = :estado WHERE id = :id"
        );
        return $stmt->execute([':estado' => $estado, ':id' => $id]);
    }
}
