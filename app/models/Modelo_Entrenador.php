<?php
require_once __DIR__ . '/Conexion.php';

/**
 * Clase Modelo_Entrenador
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 60
 */
class Modelo_Entrenador {

    /**
     * Obtiene los datos de un entrenador por su ID
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function obtenerPorIdBD($id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("SELECT id, name, password FROM entrenador WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Actualiza los datos del entrenador en la BD
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function actualizarEntrenadorBD($id, $name, $password) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("UPDATE entrenador SET name = :name, password = :password WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':password' => $password
        ]);
    }

    /**
     * Elimina la cuenta de un entrenador de la BD
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function eliminarEntrenadorBD($id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM entrenador WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Obtiene el entrenador por email o nombre de usuario
     * Utilizado en CU1: Iniciar Sesion
     */
    public function obtenerUsuarioPorEmailBD($emailOrName) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("SELECT id, name, password FROM entrenador WHERE name = :name");
        $stmt->execute([':name' => $emailOrName]);
        return $stmt->fetch();
    }

    /**
     * Valida las credenciales del entrenador en la BD
     * Utilizado en CU1: Iniciar Sesion
     */
    public function validarCredencialesBD($name, $password) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("SELECT id, name FROM entrenador WHERE name = :name AND password = :password");
        $stmt->execute([':name' => $name, ':password' => $password]);
        return $stmt->fetch();
    }
}
