<?php
/**
 * Front Controller Principal del Sistema
 * Arquitectura de Software MVC
 * Basado estrictamente en los Diagramas de Enterprise Architect
 */
session_start();

// Rutas de Controladores
require_once __DIR__ . '/app/controllers/Controlador_Auth.php';
require_once __DIR__ . '/app/controllers/Controlador_Cliente.php';
require_once __DIR__ . '/app/controllers/Controlador_Ejercicio.php';
require_once __DIR__ . '/app/controllers/Controlador_Rutina.php';
require_once __DIR__ . '/app/controllers/Controlador_Entrenador.php';

// Parámetros de petición
$controllerParam = $_GET['c'] ?? 'Auth';
$actionParam = $_GET['a'] ?? 'mostrarFormulario';

// Mapeo seguro de Controladores según Enterprise Architect
$controllersMap = [
    'Auth' => 'Controlador_Auth',
    'Cliente' => 'Controlador_Cliente',
    'Ejercicio' => 'Controlador_Ejercicio',
    'Rutina' => 'Controlador_Rutina',
    'Entrenador' => 'Controlador_Entrenador'
];

if (!array_key_exists($controllerParam, $controllersMap)) {
    $controllerParam = 'Auth';
    $actionParam = 'mostrarFormulario';
}

$controllerClass = $controllersMap[$controllerParam];
$controllerInstance = new $controllerClass();

// Control de Acceso: Verificar si requiere sesión activa (excepto en Auth)
if ($controllerParam !== 'Auth' && !isset($_SESSION['usuario'])) {
    header("Location: index.php?c=Auth&a=mostrarFormulario");
    exit();
}

// Ejecutar la acción si existe en el controlador
if (method_exists($controllerInstance, $actionParam)) {
    $controllerInstance->$actionParam();
} else {
    // Si la acción no existe o es por defecto
    if ($controllerParam === 'Auth') {
        $controllerInstance->mostrarFormulario();
    } elseif ($controllerParam === 'Cliente') {
        $controllerInstance->listarClientes();
    } elseif ($controllerParam === 'Ejercicio') {
        $controllerInstance->listarEjerciciosConMultimedia();
    } elseif ($controllerParam === 'Rutina') {
        $controllerInstance->listarHistorialRutinas();
    } elseif ($controllerParam === 'Entrenador') {
        $controllerInstance->consultarPerfil();
    }
}
