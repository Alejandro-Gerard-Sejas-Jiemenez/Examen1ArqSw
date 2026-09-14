<?php
require_once __DIR__ . '/Conexion.php';

/**
 * Clase Modelo_Imagen
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 68
 */
class Modelo_Imagen
{

    /**
     * Inserta una nueva imagen asociada a un ejercicio y devuelve su ID
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function insertarImagenBD($url_iamgen, $ejercicio_id)
    {
        $db = Conexion::getConexion();
        $uploadDir = dirname(__DIR__, 2) . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $lastId = null;

        // Caso 1: Archivo(s) subido(s) desde el dispositivo ($_FILES)
        if (is_array($url_iamgen) && isset($url_iamgen['name'])) {
            if (is_array($url_iamgen['name'])) {
                // Múltiples archivos seleccionados a la vez
                foreach ($url_iamgen['name'] as $i => $origName) {
                    if (!empty($url_iamgen['tmp_name'][$i]) && $url_iamgen['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($origName, PATHINFO_EXTENSION) ?: 'jpg';
                        $nombreArchivo = 'img_' . time() . '_' . mt_rand(100, 999) . '_' . $i . '.' . $ext;
                        if (move_uploaded_file($url_iamgen['tmp_name'][$i], $uploadDir . $nombreArchivo)) {
                            $stmt = $db->prepare("INSERT INTO imagen (url_iamgen, ejercicio_id) VALUES (:url_iamgen, :ejercicio_id)");
                            $stmt->execute([
                                ':url_iamgen' => 'uploads/' . $nombreArchivo,
                                ':ejercicio_id' => $ejercicio_id
                            ]);
                            $lastId = $db->lastInsertId();
                        }
                    }
                }
            } elseif (!empty($url_iamgen['tmp_name']) && $url_iamgen['error'] === UPLOAD_ERR_OK) {
                // Un solo archivo
                $ext = pathinfo($url_iamgen['name'], PATHINFO_EXTENSION) ?: 'jpg';
                $nombreArchivo = 'img_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
                if (move_uploaded_file($url_iamgen['tmp_name'], $uploadDir . $nombreArchivo)) {
                    $stmt = $db->prepare("INSERT INTO imagen (url_iamgen, ejercicio_id) VALUES (:url_iamgen, :ejercicio_id)");
                    $stmt->execute([
                        ':url_iamgen' => 'uploads/' . $nombreArchivo,
                        ':ejercicio_id' => $ejercicio_id
                    ]);
                    $lastId = $db->lastInsertId();
                }
            }
        } elseif (is_string($url_iamgen) && !empty(trim($url_iamgen))) {
            // Caso 2: URLs de texto (soporta una o varias separadas por coma)
            $urls = array_map('trim', explode(',', $url_iamgen));
            foreach ($urls as $u) {
                if (!empty($u)) {
                    $stmt = $db->prepare("INSERT INTO imagen (url_iamgen, ejercicio_id) VALUES (:url_iamgen, :ejercicio_id)");
                    $stmt->execute([
                        ':url_iamgen' => $u,
                        ':ejercicio_id' => $ejercicio_id
                    ]);
                    $lastId = $db->lastInsertId();
                }
            }
        }

        return $lastId;
    }

    /**
     * Elimina una imagen de la BD
     */
    public function eliminarImagenBD($id)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM imagen WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
