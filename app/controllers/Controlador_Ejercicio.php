<?php
require_once __DIR__ . '/../models/Modelo_Ejercicio.php';
require_once __DIR__ . '/../models/Modelo_Imagen.php';
require_once __DIR__ . '/../models/Modelo_Video.php';
require_once __DIR__ . '/../views/Vista_Ejercicio.php';

/**
 * Clase Controlador_Ejercicio
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia (CU4)
 * Paquete: Diagramas de Desplazamiento / Controller
 * ElementID: 53
 */
class Controlador_Ejercicio {

    private $modeloEjercicio;
    private $modeloImagen;
    private $modeloVideo;
    private $vistaEjercicio;

    public function __construct() {
        $this->modeloEjercicio = new Modelo_Ejercicio();
        $this->modeloImagen = new Modelo_Imagen();
        $this->modeloVideo = new Modelo_Video();
        $this->vistaEjercicio = new Vista_Ejercicio();
    }

    /**
     * Muestra la lista/catálogo de ejercicios con imágenes y videos
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function listarEjerciciosConMultimedia() {
        $catalogo = $this->modeloEjercicio->consultarConMultimediaBD();
        $this->vistaEjercicio->desplegarCatalogoEjercicios($catalogo);
    }

    /**
     * Muestra formulario para capturar datos
     */
    public function capturarDatosEjercicioConArchivos() {
        $this->vistaEjercicio->capturarDatosEjercicioConArchivos();
    }

    /**
     * Crea un ejercicio completo incluyendo la inserción de imágenes y videos
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function crearEjercicioCompleto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $urlImagen = trim($_POST['url_iamgen'] ?? '');
            $urlVideo = trim($_POST['url_video'] ?? '');

            if (!empty($nombre) && !empty($urlImagen)) {
                // 1. Insertar Ejercicio
                $ejercicioId = $this->modeloEjercicio->insertarEjercicioBD($nombre, $descripcion);

                // 2. Insertar Imagen (Secuencia CU4)
                $this->modeloImagen->insertarImagenBD($urlImagen, $ejercicioId);

                // 3. Insertar Video si se proporciona (Secuencia CU4)
                if (!empty($urlVideo)) {
                    $this->modeloVideo->insertarVideoBD($urlVideo, $ejercicioId);
                }

                header("Location: index.php?c=Ejercicio&a=listarEjerciciosConMultimedia&msg=" . urlencode("Ejercicio creado exitosamente."));
                exit();
            }
        }
        $this->vistaEjercicio->capturarDatosEjercicioConArchivos();
    }

    /**
     * Modifica los datos de un ejercicio existente
     */
    public function modificarEjercicioCompleto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');

            if ($id > 0 && !empty($nombre)) {
                $this->modeloEjercicio->actualizarEjercicioBD($id, $nombre, $descripcion);
                header("Location: index.php?c=Ejercicio&a=listarEjerciciosConMultimedia&msg=" . urlencode("Ejercicio modificado exitosamente."));
                exit();
            }
        }
        $this->listarEjerciciosConMultimedia();
    }

    /**
     * Remueve un ejercicio y sus recursos multimedia asociados
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function removerEjercicioCompleto() {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->modeloEjercicio->eliminarEjercicioBD($id);
            header("Location: index.php?c=Ejercicio&a=listarEjerciciosConMultimedia&msg=" . urlencode("Ejercicio eliminado exitosamente."));
            exit();
        }
        $this->listarEjerciciosConMultimedia();
    }
}
