<?php
/**
 * Controlador de Categorías — Biblioteca Fusalmo
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Libro.php';

class CategoriaController {

    private Categoria $categoriaModel;
    private Libro $libroModel;

    public function __construct() {
        Session::start();
        $this->categoriaModel = new Categoria();
        $this->libroModel = new Libro();
    }

    public function index(): void {
        Session::requireRole(['admin', 'bibliotecario']);
        $categorias = $this->categoriaModel->getAll();
        require_once __DIR__ . '/../views/categorias/index.php';
    }

    public function showCreate(): void {
        Session::requireRole(['admin']);
        require_once __DIR__ . '/../views/categorias/create.php';
    }

    public function processCreate(): void {
        Session::requireRole(['admin']);
        Security::validateCSRF();

        $nombre = Security::sanitizeString($_POST['nombre'] ?? '');
        $descripcion = Security::sanitizeString($_POST['descripcion'] ?? '');

        $errors = [];
        if (strlen($nombre) < 3) $errors[] = 'Nombre debe tener al menos 3 caracteres.';
        if ($this->categoriaModel->existsByName($nombre)) $errors[] = 'Categoría ya existe.';

        if (!empty($errors)) {
            $_SESSION['cat_errors'] = $errors;
            $_SESSION['cat_data'] = compact('nombre', 'descripcion');
            header('Location: index.php?page=categorias&action=create');
            exit();
        }

        if ($this->categoriaModel->create(compact('nombre', 'descripcion'))) {
            $_SESSION['success'] = 'Categoría creada.';
            header('Location: index.php?page=categorias');
        } else {
            $_SESSION['error'] = 'Error al crear categoría.';
            header('Location: index.php?page=categorias&action=create');
        }
        exit();
    }

    public function showEdit(): void {
        Session::requireRole(['admin']);
        
        $id = Security::sanitizeInt($_GET['id'] ?? 0);
        $categoria = $this->categoriaModel->getById($id);
        
        if (!$categoria) {
            $_SESSION['error'] = 'Categoría no encontrada.';
            header('Location: index.php?page=categorias');
            exit();
        }

        require_once __DIR__ . '/../views/categorias/edit.php';
    }

    public function processEdit(): void {
        Session::requireRole(['admin']);
        Security::validateCSRF();

        $id = Security::sanitizeInt($_POST['id'] ?? 0);
        $nombre = Security::sanitizeString($_POST['nombre'] ?? '');
        $descripcion = Security::sanitizeString($_POST['descripcion'] ?? '');

        $errors = [];
        if (!$id || !$this->categoriaModel->getById($id)) $errors[] = 'Categoría no encontrada.';
        if (strlen($nombre) < 3) $errors[] = 'Nombre debe tener al menos 3 caracteres.';
        if ($this->categoriaModel->existsByName($nombre, $id)) $errors[] = 'Nombre ya existe.';

        if (!empty($errors)) {
            $_SESSION['cat_errors'] = $errors;
            header("Location: index.php?page=categorias&action=edit&id={$id}");
            exit();
        }

        if ($this->categoriaModel->update($id, compact('nombre', 'descripcion'))) {
            $_SESSION['success'] = 'Categoría actualizada.';
            header('Location: index.php?page=categorias');
        } else {
            $_SESSION['error'] = 'Error al actualizar.';
            header("Location: index.php?page=categorias&action=edit&id={$id}");
        }
        exit();
    }

    public function delete(): void {
        Session::requireRole(['admin']);
        Security::validateCSRF();

        $id = Security::sanitizeInt($_POST['id'] ?? 0);
        if (!$id || !$this->categoriaModel->getById($id)) {
            $_SESSION['error'] = 'Categoría no encontrada.';
        } else {
            $libros = $this->libroModel->getByCategoria($id);
            if (!empty($libros)) {
                $_SESSION['error'] = 'No se puede eliminar categoría con libros asignados.';
            } elseif ($this->categoriaModel->delete($id)) {
                $_SESSION['success'] = 'Categoría eliminada.';
            } else {
                $_SESSION['error'] = 'Error al eliminar.';
            }
        }

        header('Location: index.php?page=categorias');
        exit();
    }
}
