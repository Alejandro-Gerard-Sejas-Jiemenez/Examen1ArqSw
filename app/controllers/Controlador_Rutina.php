<?php
require_once __DIR__ . '/../models/Modelo_Rutina.php';
require_once __DIR__ . '/../models/Modelo_Detalle_Rutina.php';
require_once __DIR__ . '/../models/Modelo_Ejercicio.php';
require_once __DIR__ . '/../models/Modelo_Cliente.php';
require_once __DIR__ . '/../views/Vista_Rutina.php';
require_once __DIR__ . '/../views/Vista_Rutina_Cliente.php';

/**
 * Clase Controlador_Rutina
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia (CU5, CU7)
 * Paquete: Diagramas de Desplazamiento / Controller
 * ElementID: 55
 */
class Controlador_Rutina {

    private $modeloRutina;
    private $modeloDetalleRutina;
    private $modeloEjercicio;
    private $modeloCliente;
    private $vistaRutina;
    private $vistaRutinaCliente;

    public function __construct() {
        $this->modeloRutina = new Modelo_Rutina();
        $this->modeloDetalleRutina = new Modelo_Detalle_Rutina();
        $this->modeloEjercicio = new Modelo_Ejercicio();
        $this->modeloCliente = new Modelo_Cliente();
        $this->vistaRutina = new Vista_Rutina();
        $this->vistaRutinaCliente = new Vista_Rutina_Cliente();
    }

    /**
     * Muestra el formulario para crear una rutina transaccional
     */
    public function mostrarFormularioTransaccional() {
        $clientes = $this->modeloCliente->consultarTodosBD();
        $ejercicios = $this->modeloEjercicio->consultarConMultimediaBD();
        $this->vistaRutina->mostrarFormularioTransaccional($clientes, $ejercicios);
    }

    /**
     * Muestra el listado con el historial de rutinas creadas
     */
    public function listarHistorialRutinas() {
        $rutinas = $this->modeloRutina->consultarHistorialRutinasBD();
        $this->vistaRutina->mostrarHistorial($rutinas);
    }

    /**
     * Procesa la inserción transaccional de cabecera y lotes de detalles
     * Utilizado en CU5: Gestionar Rutina
     */
    public function procesarRutinaTransaccional() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $indicaciones = trim($_POST['indicaciones'] ?? '');
            $fechaInicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
            $fechaFin = $_POST['fecha_fin'] ?? date('Y-m-d');
            $clienteId = intval($_POST['cliente_id'] ?? 0);
            $entrenadorId = $_SESSION['usuario']['id'] ?? 1;
            $detalles = $_POST['detalles'] ?? [];

            if (!empty($nombre) && $clienteId > 0 && !empty($detalles)) {
                $db = Conexion::getConexion();
                try {
                    $db->beginTransaction();

                    // 1. Inserción de cabecera
                    $rutinaId = $this->modeloRutina->insertarCabeceraBD(
                        $nombre,
                        $indicaciones,
                        $fechaInicio,
                        $fechaFin,
                        $clienteId,
                        $entrenadorId
                    );

                    // 2. Inserción en lote de detalles
                    $this->modeloDetalleRutina->insertarDetalleLoteBD($rutinaId, $detalles);

                    $db->commit();
                    header("Location: index.php?c=Rutina&a=listarHistorialRutinas&msg=" . urlencode("Rutina transaccional procesada con éxito."));
                    exit();
                } catch (Exception $e) {
                    if ($db->inTransaction()) {
                        $db->rollBack();
                    }
                    header("Location: index.php?c=Rutina&a=mostrarFormularioTransaccional&err=" . urlencode("Error al procesar la transacción: " . $e->getMessage()));
                    exit();
                }
            }
        }
        $this->mostrarFormularioTransaccional();
    }

    /**
     * Modifica una rutina existente y sus detalles
     * Utilizado en CU5: Gestionar Rutina
     */
    public function modificarRutinaTransaccional() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            $indicaciones = trim($_POST['indicaciones'] ?? '');
            $fechaInicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
            $fechaFin = $_POST['fecha_fin'] ?? date('Y-m-d');

            if ($id > 0 && !empty($nombre)) {
                $this->modeloRutina->actualizarCabeceraBD($id, $nombre, $indicaciones, $fechaInicio, $fechaFin);
                header("Location: index.php?c=Rutina&a=listarHistorialRutinas&msg=" . urlencode("Rutina actualizada correctamente."));
                exit();
            }
        }
        $this->listarHistorialRutinas();
    }

    /**
     * Elimina una rutina y sus detalles en cascada
     * Utilizado en CU5: Gestionar Rutina
     */
    public function eliminarRutinaEnCascada() {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->modeloDetalleRutina->eliminarDetallesPorRutinaBD($id);
            $this->modeloRutina->eliminarCabeceraBD($id);
            header("Location: index.php?c=Rutina&a=listarHistorialRutinas&msg=" . urlencode("Rutina eliminada en cascada correctamente."));
            exit();
        }
        $this->listarHistorialRutinas();
    }

    /**
     * Obtiene la planificación semanal activa para el cliente en sesión
     * Utilizado en CU7: Visualizar Rutina Asignada
     */
    public function obtenerPlanificacionSemanalActiva() {
        $clienteId = $_SESSION['usuario']['id'] ?? 0;
        
        // Si no hay cliente en sesión, intentar tomar el primer cliente registrado para demostración
        if ($clienteId === 0) {
            $clientes = $this->modeloCliente->consultarTodosBD();
            if (!empty($clientes)) {
                $clienteId = $clientes[0]['id'];
            }
        }

        $rutina = $this->modeloRutina->consultarRutinaVigenteBD($clienteId);
        $detalles = [];

        if ($rutina) {
            $detalles = $this->modeloDetalleRutina->consultarEjerciciosAsignadosBD($rutina['id']);
        }

        $this->vistaRutinaCliente->desplegarRutinaVigente($rutina, $detalles);
    }

    /**
     * Obtiene los recursos multimedia de un ejercicio específico
     * Utilizado en CU7: Visualizar Rutina Asignada
     */
    public function obtenerRecursosEjercicio($ejercicioId) {
        return $this->modeloEjercicio->obtenerRecursosMultimediaBD($ejercicioId);
    }
}
