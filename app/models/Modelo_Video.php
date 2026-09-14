<?php
require_once __DIR__ . '/Conexion.php';

/**
 * Clase Modelo_Video
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 67
 */
class Modelo_Video
{

    /**
     * Inserta un nuevo video asociado a un ejercicio y devuelve su ID
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function insertarVideoBD($url_video, $ejercicio_id)
    {
        // Si se envió un archivo desde el dispositivo ($_FILES), gestiona el almacenamiento físico
        if (is_array($url_video) && !empty($url_video['tmp_name']) && $url_video['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extVid = pathinfo($url_video['name'], PATHINFO_EXTENSION) ?: 'mp4';
            $nombreVideo = 'vid_' . time() . '_' . mt_rand(100, 999) . '.' . $extVid;
            if (move_uploaded_file($url_video['tmp_name'], $uploadDir . $nombreVideo)) {
                $url_video = 'app/models/uploads/' . $nombreVideo;
            } else {
                $url_video = '';
            }
        }

        $db = Conexion::getConexion();
        $stmt = $db->prepare("INSERT INTO video (url_video, ejercicio_id) VALUES (:url_video, :ejercicio_id)");
        $stmt->execute([
            ':url_video' => is_string($url_video) ? $url_video : '',
            ':ejercicio_id' => $ejercicio_id
        ]);
        return $db->lastInsertId();
    }

    /**
     * Elimina un video de la BD
     */
    public function eliminarVideoBD($id)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM video WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
