# Proyecto PHP MVC - Sistema de Gestión de Rutinas y Entrenamientos

Proyecto desarrollado estrictamente en base a los modelos y especificaciones de **Enterprise Architect**:
- **Diagrama Conceptual:** Clases del dominio, atributos y relaciones.
- **Identificar Módulos:** `Control de Acceso` y `Planificación de Entrenamientos`.
- **Relación de Módulos con Casos de Uso:** Mapeo de `CU1` al `CU7`.
- **Diseño de la Arquitectura:** Arquitectura MVC estructurada por subsistemas.
- **Diseño de Clase Dinámica / Desplazamiento:** Clases concretas de Vistas, Controladores, Modelos y Conexión.
- **Diagramas de Secuencia (Carpeta `prueba`):** Flujo de interacción de llamadas para cada Caso de Uso.

---

## 🏗️ Arquitectura del Proyecto (MVC)

```
examne1Arq/
├── app/
│   ├── models/                 # Modelos del Paquete 21 (Model) + Persistencia
│   │   ├── Conexion.php        # Clase Conexion con método getConexion() (PostgreSQL)
│   │   ├── schema.sql          # Script DDL de PostgreSQL con datos iniciales
│   │   ├── Modelo_Cliente.php
│   │   ├── Modelo_Entrenador.php
│   │   ├── Modelo_Ejercicio.php
│   │   ├── Modelo_Imagen.php
│   │   ├── Modelo_Video.php
│   │   ├── Modelo_Rutina.php
│   │   └── Modelo_Detalle_Rutina.php
│   ├── controllers/            # Controladores del Paquete 22 (Controller)
│   │   ├── Controlador_Auth.php
│   │   ├── Controlador_Cliente.php
│   │   ├── Controlador_Ejercicio.php
│   │   ├── Controlador_Rutina.php
│   │   └── Controlador_Entrenador.php
│   └── views/                  # Vistas del Paquete 20 (Vista)
│       ├── Vista_Layout.php
│       ├── Vista_Login.php
│       ├── Vista_Cliente.php
│       ├── Vista_Ejercicio.php
│       ├── Vista_Rutina.php
│       ├── Vista_Rutina_Cliente.php
│       ├── Vista_Perfil_Entrenador.php
│       └── css/                # Estilos CSS modulares de la Vista
│           ├── style.css       # Master Stylesheet (Orquestador @import)
│           ├── variables.css   # Tokens, colores y tipografía Inter
│           ├── base.css        # Reset y estándares base
│           ├── layout.css      # Header, navegación y layout principal
│           ├── components.css  # Botones, tablas, tarjetas, alertas y modales
│           └── views.css       # Estilos de vistas específicas
├── index.php                   # Front Controller / Enrutador
└── README.md
```

---

## 🔑 Cuentas de Acceso para Pruebas

| Rol | Identificador | Contraseña | Caso de Uso Principal |
| :--- | :--- | :--- | :--- |
| **Entrenador** | `Carlos Entrenador` | `123456` | CU3 (Clientes), CU4 (Ejercicios), CU5 (Rutinas), CU6 (Perfil) |
| **Cliente** | `juan@gmail.com` | `123456` | CU7 (Visualizar Rutina Asignada con video de apoyo) |

---

## 🚀 Cómo Ejecutar el Proyecto

### Opción 1: Servidor Integrado de PHP (Sin configuraciones previas)
En la consola de la carpeta del proyecto, ejecuta:
```bash
php -S localhost:8000
```
Luego ingresa en tu navegador a:
`http://localhost:8000`

> **Nota:** La clase `Conexion` detectará si no hay un servidor MySQL activo y utilizará automáticamente la base de datos SQLite integrada con todos los datos de prueba precargados.

### Opción 2: XAMPP / WampServer / Laragon
1. Copia o mueve la carpeta `examne1Arq` dentro de `htdocs` (en XAMPP) o `www` (en WampServer).
2. Crea una base de datos llamada `gym_db` en phpMyAdmin.
3. Importa el archivo `database/schema.sql`.
4. Accede desde tu navegador a:
   `http://localhost/examne1Arq/`

---

## 📊 Mapeo 1:1 de Clases y Operaciones con Enterprise Architect

### 1. Modelos (`app/models/`)
- **`Modelo_Cliente`:** `consultarTodosBD()`, `actualizarClienteBD()`, `eliminarClienteBD()`, `insertarClienteBD()`, `validarCredencialesBD()`, `obtenerUsuarioPorEmailBD()`.
- **`Modelo_Entrenador`:** `obtenerPorIdBD()`, `actualizarEntrenadorBD()`, `eliminarEntrenadorBD()`, `obtenerUsuarioPorEmailBD()`, `validarCredencialesBD()`.
- **`Modelo_Ejercicio`:** `insertarEjercicioBD()`, `actualizarEjercicioBD()`, `eliminarEjercicioBD()`, `consultarConMultimediaBD()`, `obtenerRecursosMultimediaBD()`.
- **`Modelo_Imagen`:** `insertarImagenBD()`, `eliminarImagenBD()`.
- **`Modelo_Video`:** `insertarVideoBD()`, `eliminarVideoBD()`.
- **`Modelo_Rutina`:** `insertarCabeceraBD()`, `actualizarCabeceraBD()`, `eliminarCabeceraBD()`, `consultarRutinaVigenteBD()`.
- **`Modelo_Detalle_Rutina`:** `insertarDetalleLoteBD()`, `eliminarDetallesPorRutinaBD()`, `consultarEjerciciosAsignadosBD()`.

### 2. Controladores (`app/controllers/`)
- **`Controlador_Auth`:** `iniciarSesion()`, `verificarRolUsuario()`, `finalizarSesionUsuario()`, `invalidarTokenAcceso()`.
- **`Controlador_Cliente`:** `modificarCliente()`, `registrarCliente()`, `listarClientes()`, `removerCliente()`.
- **`Controlador_Ejercicio`:** `listarEjerciciosConMultimedia()`, `crearEjercicioCompleto()`, `modificarEjercicioCompleto()`, `removerEjercicioCompleto()`.
- **`Controlador_Rutina`:** `listarHistorialRutinas()`, `procesarRutinaTransaccional()`, `modificarRutinaTransaccional()`, `eliminarRutinaEnCascada()`, `obtenerPlanificacionSemanalActiva()`, `obtenerRecursosEjercicio()`.
- **`Controlador_Entrenador`:** `consultarPerfil()`, `actualizarPerfilEntrenador()`, `darDeBajaEntrenador()`.

### 3. Vistas (`app/views/`)
- **`Vista_Layout`:** `solicitarCierreSesion()`, `redirigirAFormularioLogin()`.
- **`Vista_Login`:** `mostrarFormulario()`, `capturarCredenciales()`, `mostrarErrorAutenticacion()`.
- **`Vista_Cliente`:** `capturarDatosCliente()`, `desplegarTablaClientes()`, `solicitarConfirmacionBaja()`.
- **`Vista_Ejercicio`:** `desplegarCatalogoEjercicios()`, `capturarDatosEjercicioConArchivos()`, `confirmarEliminacionEjercicio()`.
- **`Vista_Rutina`:** `mostrarFormularioTransaccional()`, `agregarEjercicioATablaTemporal()`, `capturarRutinaYDetalles()`.
- **`Vista_Rutina_Cliente`:** `desplegarRutinaVigente()`, `seleccionarEjercicioDetalle()`, `reproducirVideoApoyo()`.
- **`Vista_Perfil_Entrenador`:** `mostrarDatosPerfil()`, `capturarCambiosPerfil()`, `solicitarBajaCuenta()`.

### 4. Conexión (`config/`)
- **`Conexion`:** `getConexion()`.
# Examen1ArqSw
