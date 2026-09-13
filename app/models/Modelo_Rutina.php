<?php
require_once __DIR__ . '/Conexion.php';

/**
 * Clase Modelo_Rutina
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 61
 */
class Modelo_Rutina
{

    /**
     * Inserta la cabecera de la rutina y devuelve el ID generado
     * Utilizado en CU5: Gestionar Rutina
     */
    public function insertarCabeceraBD($nombre, $indicaciones, $fecha_inicio, $fecha_fin, $cliente_id, $entrenador_id)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("INSERT INTO rutina (nombre, indicaciones, fecha_inicio, fecha_fin, cliente_id, entrenador_id) 
                              VALUES (:nombre, :indicaciones, :fecha_inicio, :fecha_fin, :cliente_id, :entrenador_id)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':indicaciones' => $indicaciones,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':cliente_id' => $cliente_id,
            ':entrenador_id' => $entrenador_id
        ]);
        return $db->lastInsertId();
    }

    /**
     * Actualiza la cabecera de una rutina
     * Utilizado en CU5: Gestionar Rutina
     */
    public function actualizarCabeceraBD($id, $nombre, $indicaciones, $fecha_inicio, $fecha_fin)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("UPDATE rutina SET nombre = :nombre, indicaciones = :indicaciones, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':indicaciones' => $indicaciones,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin
        ]);
    }

    /**
     * Elimina la cabecera de la rutina
     * Utilizado en CU5: Gestionar Rutina
     */
    public function eliminarCabeceraBD($id)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM rutina WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Consulta la rutina activa/vigente asignada a un cliente
     * Utilizado en CU7: Visualizar Rutina Asignada
     */
    public function consultarRutinaVigenteBD($cliente_id)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("SELECT r.*, e.name AS nombre_entrenador, c.name AS nombre_cliente 
                              FROM rutina r
                              INNER JOIN entrenador e ON r.entrenador_id = e.id
                              INNER JOIN cliente c ON r.cliente_id = c.id
                              WHERE r.cliente_id = :cliente_id
                              ORDER BY r.id DESC LIMIT 1");
        $stmt->execute([':cliente_id' => $cliente_id]);
        return $stmt->fetch();
    }

    /**
     * Consulta el historial de todas las rutinas registradas con el nombre de su cliente
     */
    public function consultarHistorialRutinasBD()
    {
        $db = Conexion::getConexion();
        $sql = "SELECT r.*, c.name AS nombre_cliente 
                FROM rutina r 
                LEFT JOIN cliente c ON r.cliente_id = c.id 
                ORDER BY r.id DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }
}
