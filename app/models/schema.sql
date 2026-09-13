-- =========================================================
-- Script de Base de Datos para PostgreSQL (Sistema Gym Architect)
-- Ubicado en el paquete Modelo según la arquitectura MVC
-- =========================================================

CREATE TABLE IF NOT EXISTS entrenador (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS cliente (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    peso DECIMAL(5,2) DEFAULT NULL,
    altura DECIMAL(5,2) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS ejercicio (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS imagen (
    id SERIAL PRIMARY KEY,
    url_iamgen VARCHAR(255) NOT NULL,
    ejercicio_id INT NOT NULL REFERENCES ejercicio(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS video (
    id SERIAL PRIMARY KEY,
    url_video VARCHAR(255) NOT NULL,
    ejercicio_id INT NOT NULL REFERENCES ejercicio(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS rutina (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    indicaciones TEXT DEFAULT NULL,
    fecha_inicio DATE DEFAULT NULL,
    fecha_fin DATE DEFAULT NULL,
    cliente_id INT NOT NULL REFERENCES cliente(id) ON DELETE CASCADE,
    entrenador_id INT NOT NULL REFERENCES entrenador(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS detalle_rutina (
    id SERIAL PRIMARY KEY,
    rutina_id INT NOT NULL REFERENCES rutina(id) ON DELETE CASCADE,
    ejercicio_id INT NOT NULL REFERENCES ejercicio(id) ON DELETE CASCADE,
    series INT NOT NULL,
    repeticiones INT NOT NULL,
    tiempo VARCHAR(50) DEFAULT NULL
);

-- Datos iniciales de prueba (Seed Data)
INSERT INTO entrenador (id, name, password) VALUES 
(1, 'Carlos Entrenador', '123456')
ON CONFLICT (id) DO NOTHING;

INSERT INTO cliente (id, name, email, password, peso, altura) VALUES 
(1, 'Juan Perez', 'juan@gmail.com', '123456', 75.50, 1.78),
(2, 'Maria Lopez', 'maria@gmail.com', '123456', 62.00, 1.65)
ON CONFLICT (id) DO NOTHING;

INSERT INTO ejercicio (id, nombre, descripcion) VALUES 
(1, 'Sentadillas Profundas', 'Flexión de rodillas manteniendo la espalda recta.'),
(2, 'Press de Banca', 'Empuje con barra en banco plano para pectoral.'),
(3, 'Plancha Abdominal', 'Sostener posición isométrica en antebrazos.')
ON CONFLICT (id) DO NOTHING;

INSERT INTO imagen (id, url_iamgen, ejercicio_id) VALUES 
(1, 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=500', 1),
(2, 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=500', 2),
(3, 'https://images.unsplash.com/photo-1566241142559-40e1dab266c6?w=500', 3)
ON CONFLICT (id) DO NOTHING;

INSERT INTO video (id, url_video, ejercicio_id) VALUES 
(1, 'https://www.w3schools.com/html/mov_bbb.mp4', 1),
(2, 'https://www.w3schools.com/html/mov_bbb.mp4', 2),
(3, 'https://www.w3schools.com/html/mov_bbb.mp4', 3)
ON CONFLICT (id) DO NOTHING;

INSERT INTO rutina (id, nombre, indicaciones, fecha_inicio, fecha_fin, cliente_id, entrenador_id) VALUES 
(1, 'Rutina Hipertrofia Semana 1', 'Descansar 90 segundos entre series y mantenerse hidratado.', '2026-09-01', '2026-09-30', 1, 1)
ON CONFLICT (id) DO NOTHING;

INSERT INTO detalle_rutina (id, rutina_id, ejercicio_id, series, repeticiones, tiempo) VALUES 
(1, 1, 1, 4, 12, '60 seg'),
(2, 1, 2, 4, 10, '90 seg'),
(3, 1, 3, 3, 1, '45 seg')
ON CONFLICT (id) DO NOTHING;

-- Sincronizar secuencias de PostgreSQL para evitar conflictos con futuros INSERT
SELECT setval(pg_get_serial_sequence('entrenador', 'id'), COALESCE((SELECT MAX(id) FROM entrenador), 1));
SELECT setval(pg_get_serial_sequence('cliente', 'id'), COALESCE((SELECT MAX(id) FROM cliente), 1));
SELECT setval(pg_get_serial_sequence('ejercicio', 'id'), COALESCE((SELECT MAX(id) FROM ejercicio), 1));
SELECT setval(pg_get_serial_sequence('imagen', 'id'), COALESCE((SELECT MAX(id) FROM imagen), 1));
SELECT setval(pg_get_serial_sequence('video', 'id'), COALESCE((SELECT MAX(id) FROM video), 1));
SELECT setval(pg_get_serial_sequence('rutina', 'id'), COALESCE((SELECT MAX(id) FROM rutina), 1));
SELECT setval(pg_get_serial_sequence('detalle_rutina', 'id'), COALESCE((SELECT MAX(id) FROM detalle_rutina), 1));
