-- Script de Base de Datos para el Sistema de Gestión de Rutinas y Entrenamientos
-- Basado estrictamente en el Diagrama Conceptual de Enterprise Architect

CREATE TABLE IF NOT EXISTS entrenador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    peso DECIMAL(5,2) DEFAULT NULL,
    altura DECIMAL(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ejercicio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS imagen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url_iamgen VARCHAR(255) NOT NULL,
    ejercicio_id INT NOT NULL,
    CONSTRAINT fk_imagen_ejercicio FOREIGN KEY (ejercicio_id) REFERENCES ejercicio(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS video (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url_video VARCHAR(255) NOT NULL,
    ejercicio_id INT NOT NULL,
    CONSTRAINT fk_video_ejercicio FOREIGN KEY (ejercicio_id) REFERENCES ejercicio(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS rutina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    indicaciones TEXT DEFAULT NULL,
    fecha_inicio DATE DEFAULT NULL,
    fecha_fin DATE DEFAULT NULL,
    cliente_id INT NOT NULL,
    entrenador_id INT NOT NULL,
    CONSTRAINT fk_rutina_cliente FOREIGN KEY (cliente_id) REFERENCES cliente(id) ON DELETE CASCADE,
    CONSTRAINT fk_rutina_entrenador FOREIGN KEY (entrenador_id) REFERENCES entrenador(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS detalle_rutina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rutina_id INT NOT NULL,
    ejercicio_id INT NOT NULL,
    series INT NOT NULL,
    repeticiones INT NOT NULL,
    tiempo VARCHAR(50) DEFAULT NULL,
    CONSTRAINT fk_detalle_rutina FOREIGN KEY (rutina_id) REFERENCES rutina(id) ON DELETE CASCADE,
    CONSTRAINT fk_detalle_ejercicio FOREIGN KEY (ejercicio_id) REFERENCES ejercicio(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos iniciales de prueba (Seed Data)
INSERT INTO entrenador (id, name, password) VALUES 
(1, 'Carlos Entrenador', '123456')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO cliente (id, name, email, password, peso, altura) VALUES 
(1, 'Juan Perez', 'juan@gmail.com', '123456', 75.50, 1.78),
(2, 'Maria Lopez', 'maria@gmail.com', '123456', 62.00, 1.65)
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO ejercicio (id, nombre, descripcion) VALUES 
(1, 'Sentadillas Profundas', 'Flexión de rodillas manteniendo la espalda recta.'),
(2, 'Press de Banca', 'Empuje con barra en banco plano para pectoral.'),
(3, 'Plancha Abdominal', 'Sostener posición isométrica en antebrazos.')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

INSERT INTO imagen (id, url_iamgen, ejercicio_id) VALUES 
(1, 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=500', 1),
(2, 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=500', 2),
(3, 'https://images.unsplash.com/photo-1566241142559-40e1dab266c6?w=500', 3)
ON DUPLICATE KEY UPDATE url_iamgen=VALUES(url_iamgen);

INSERT INTO video (id, url_video, ejercicio_id) VALUES 
(1, 'https://www.w3schools.com/html/mov_bbb.mp4', 1),
(2, 'https://www.w3schools.com/html/mov_bbb.mp4', 2),
(3, 'https://www.w3schools.com/html/mov_bbb.mp4', 3)
ON DUPLICATE KEY UPDATE url_video=VALUES(url_video);

INSERT INTO rutina (id, nombre, indicaciones, fecha_inicio, fecha_fin, cliente_id, entrenador_id) VALUES 
(1, 'Rutina Hipertrofia Semana 1', 'Descansar 90 segundos entre series y mantenerse hidratado.', '2026-09-01', '2026-09-30', 1, 1)
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

INSERT INTO detalle_rutina (id, rutina_id, ejercicio_id, series, repeticiones, tiempo) VALUES 
(1, 1, 1, 4, 12, '60 seg'),
(2, 1, 2, 4, 10, '90 seg'),
(3, 1, 3, 3, 1, '45 seg')
ON DUPLICATE KEY UPDATE series=VALUES(series);
