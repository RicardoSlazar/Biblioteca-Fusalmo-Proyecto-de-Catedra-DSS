<?php
/**
 * Controlador de Autores — Biblioteca Fusalmo
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../models/Autor.php';
require_once __DIR__ . '/../models/Libro.php';

class AutorController {

    private Autor $autorModel;
    private Libro $libroModel;

    public function __construct() {
        Session::start();
        $this->autorModel = new Autor();
        $this->libroModel = new Libro();
    }

    public function index(): void {
        Session::requireRole(['admin', 'bibliotecario']);
        $autores = $this->autorModel->getAll();
        require_once __DIR__ . '/../views/autores/index.php';
    }

    public function showCreate(): void {
        Session::requireRole(['admin']);
        require_once __DIR__ . '/../views/autores/create.php';
    }

    public function processCreate(): void {
        Session::requireRole(['admin']);
        Security::validateCSRF();

        $nombre = Security::sanitizeString($_POST['nombre'] ?? '');
        $nacionalidad = Security::sanitizeString($_POST['nacionalidad'] ?? '');

        $errors = [];
        if (strlen($nombre) < 3) $errors[] = 'Nombre debe tener al menos 3 caracteres.';
        if ($this->autorModel->existsByName($nombre)) $errors[] = 'Autor ya existe.';

        if (!empty($errors)) {
            $_SESSION['aut_errors'] = $errors;
            $_SESSION['aut_data'] = compact('nombre', 'nacionalidad');
            header('Location: /index.php?page=autores&action=create');
            exit();
        }

        if ($this->autorModel->create(compact('nombre', 'nacionalidad'))) {
            $_SESSION['success'] = 'Autor creado.';
            header('Location: /index.php?page=autores');
        } else {
            $_SESSION['error'] = 'Error al crear autor.';
            header('Location: /index.php?page=autores&action=create');
        }
        exit();
    }

    public function showEdit(): void {
        Session::requireRole(['admin']);
        
        $id = Security::sanitizeInt($_GET['id'] ?? 0);
        $autor = $this->autorModel->getById($id);
        
        if (!$autor) {
            $_SESSION['error'] = 'Autor no encontrado.';
            header('Location: /index.php?page=autores');
            exit();
        }

        require_once __DIR__ . '/../views/autores/edit.php';
    }

    public function processEdit(): void {
        Session::requireRole(['admin']);
        Security::validateCSRF();

        $id = Security::sanitizeInt($_POST['id'] ?? 0);
        $nombre = Security::sanitizeString($_POST['nombre'] ?? '');
        $nacionalidad = Security::sanitizeString($_POST['nacionalidad'] ?? '');

        $errors = [];
        if (!$id || !$this->autorModel->getById($id)) $errors[] = 'Autor no encontrado.';
        if (strlen($nombre) < 3) $errors[] = 'Nombre debe tener al menos 3 caracteres.';
        if ($this->autorModel->existsByName($nombre, $id)) $errors[] = 'Nombre ya existe.';

        if (!empty($errors)) {
            $_SESSION['aut_errors'] = $errors;
            header("Location: /index.php?page=autores&action=edit&id={$id}");
            exit();
        }

        if ($this->autorModel->update($id, compact('nombre', 'nacionalidad'))) {
            $_SESSION['success'] = 'Autor actualizado.';
            header('Location: /index.php?page=autores');
        } else {
            $_SESSION['error'] = 'Error al actualizar.';
            header("Location: /index.php?page=autores&action=edit&id={$id}");
        }
        exit();
    }

    public function delete(): void {
        Session::requireRole(['admin']);
        Security::validateCSRF();

        $id = Security::sanitizeInt($_POST['id'] ?? 0);
        if (!$id || !$this->autorModel->getById($id)) {
            $_SESSION['error'] = 'Autor no encontrado.';
        } else {
            $libros = $this->libroModel->getByAutor($id);
            if (!empty($libros)) {
                $_SESSION['error'] = 'No se puede eliminar autor con libros asignados.';
            } elseif ($this->autorModel->delete($id)) {
                $_SESSION['success'] = 'Autor eliminado.';
            } else {
                $_SESSION['error'] = 'Error al eliminar.';
            }
        }

        header('Location: /index.php?page=autores');
        exit();
    }
}
