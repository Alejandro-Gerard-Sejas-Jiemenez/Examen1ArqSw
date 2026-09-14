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
     * Crea un ejercicio completo incluyendo la inserción de imágenes y videos
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function crearEjercicioCompleto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            // Recurso de imagen: archivo(s) cargado(s) desde el dispositivo o URL(s) escrita(s)
            $hayArchivoImagen = false;
            if (isset($_FILES['archivo_imagen']['name'])) {
                if (is_array($_FILES['archivo_imagen']['name'])) {
                    $hayArchivoImagen = !empty($_FILES['archivo_imagen']['name'][0]);
                } else {
                    $hayArchivoImagen = !empty($_FILES['archivo_imagen']['tmp_name']);
                }
            }
            $recursoImagen = $hayArchivoImagen ? $_FILES['archivo_imagen'] : trim($_POST['url_iamgen'] ?? '');
            // Recurso de video: archivo cargado desde el dispositivo o URL escrita
            $recursoVideo = (!empty($_FILES['archivo_video']['tmp_name'])) ? $_FILES['archivo_video'] : trim($_POST['url_video'] ?? '');

            if (!empty($nombre) && !empty($recursoImagen)) {
                // 1. Insertar Ejercicio en Modelo_Ejercicio
                $ejercicioId = $this->modeloEjercicio->insertarEjercicioBD($nombre, $descripcion);

                // 2. Insertar Imagen en Modelo_Imagen (gestiona almacenamiento y BD)
                $this->modeloImagen->insertarImagenBD($recursoImagen, $ejercicioId);

                // 3. Insertar Video en Modelo_Video si se proporciona
                if (!empty($recursoVideo)) {
                    $this->modeloVideo->insertarVideoBD($recursoVideo, $ejercicioId);
                }

                header("Location: index.php?c=Ejercicio&a=listarEjerciciosConMultimedia&msg=" . urlencode("Ejercicio creado exitosamente con multimedia."));
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
