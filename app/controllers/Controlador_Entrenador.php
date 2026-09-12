<?php
require_once __DIR__ . '/../models/Modelo_Entrenador.php';
require_once __DIR__ . '/../views/Vista_Perfil_Entrenador.php';

/**
 * Clase Controlador_Entrenador
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia (CU6)
 * Paquete: Diagramas de Desplazamiento / Controller
 * ElementID: 54
 */
class Controlador_Entrenador
{

    private $modeloEntrenador;
    private $vistaPerfilEntrenador;

    public function __construct()
    {
        $this->modeloEntrenador = new Modelo_Entrenador();
        $this->vistaPerfilEntrenador = new Vista_Perfil_Entrenador();
    }

    /**
     * Consulta el perfil del entrenador actual y lo muestra en la vista
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function consultarPerfil()
    {
        $id = $_SESSION['usuario']['id'] ?? 1;
        $datos = $this->modeloEntrenador->obtenerPorIdBD($id);
        $this->vistaPerfilEntrenador->mostrarDatosPerfil($datos);
    }

    /**
     * Procesa la actualización de los datos del perfil del entrenador
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function actualizarPerfilEntrenador()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['usuario']['id'] ?? 1;
            $name = trim($_POST['name'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($name) && !empty($password)) {
                $this->modeloEntrenador->actualizarEntrenadorBD($id, $name, $password);
                $_SESSION['usuario']['name'] = $name;
                $_SESSION['usuario']['password'] = $password;

                header("Location: index.php?c=Entrenador&a=consultarPerfil&msg=" . urlencode("Perfil actualizado exitosamente."));
                exit();
            }
        }
        $this->consultarPerfil();
    }

    /**
     * Da de baja y elimina la cuenta del entrenador en la BD
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function darDeBajaEntrenador()
    {
        $id = $_SESSION['usuario']['id'] ?? 1;
        if ($id > 0) {
            $this->modeloEntrenador->eliminarEntrenadorBD($id);
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION = [];
                session_destroy();
            }
            header("Location: index.php?c=Auth&a=mostrarFormulario");
            exit();
        }
        $this->consultarPerfil();
    }
}
