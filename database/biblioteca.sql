CREATE DATABASE biblioteca_fusalmo;

USE biblioteca_fusalmo;

CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    isbn VARCHAR(30) UNIQUE NOT NULL,
    cantidad INT NOT NULL,
    estado ENUM('Disponible','Prestado','Dañado') DEFAULT 'Disponible',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);