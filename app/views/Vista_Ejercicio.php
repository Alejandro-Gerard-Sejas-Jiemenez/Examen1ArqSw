<?php
require_once __DIR__ . '/Vista_Layout.php';

/**
 * Clase Vista_Ejercicio
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Vista
 * ElementID: 63
 */
class Vista_Ejercicio {

    /**
     * Despliega el catálogo completo de ejercicios junto a sus recursos multimedia
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function desplegarCatalogoEjercicios($catalogo = []) {
        Vista_Layout::renderHeader("Catálogo de Ejercicios con Multimedia");
        ?>
        <div class="container">
            <div class="header-action-bar">
                <div>
                    <h2>Catálogo de Ejercicios</h2>
                    <p class="subtitle">Ejercicios disponibles con imágenes y videos demostrativos</p>
                </div>
                <a href="index.php?c=Ejercicio&a=crearEjercicioCompleto" class="btn btn-primary">Nuevo Ejercicio</a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>

            <div class="cards-grid">
                <?php if (empty($catalogo)): ?>
                    <p class="text-center">No hay ejercicios registrados.</p>
                <?php else: ?>
                    <?php foreach ($catalogo as $ej): ?>
                        <div class="card exercise-card">
                            <div class="card-media">
                                <?php if (!empty($ej['imagen_url'])): ?>
                                    <img src="<?= htmlspecialchars($ej['imagen_url']) ?>" alt="<?= htmlspecialchars($ej['nombre']) ?>" class="exercise-img">
                                <?php else: ?>
                                    <div class="no-image">Sin imagen disponible</div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h3><?= htmlspecialchars($ej['nombre']) ?></h3>
                                <p class="description"><?= htmlspecialchars($ej['descripcion']) ?></p>

                                <?php if (!empty($ej['video_url'])): ?>
                                    <div class="video-container">
                                        <video controls class="exercise-video">
                                             <source src="<?= htmlspecialchars($ej['video_url']) ?>" type="video/mp4">
                                            Tu navegador no soporta el video.
                                        </video>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <?= $this->confirmarEliminacionEjercicio($ej['id']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        Vista_Layout::renderFooter();
    }

    /**
     * Muestra la interfaz para registrar un ejercicio con enlaces o archivos multimedia
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function capturarDatosEjercicioConArchivos() {
        Vista_Layout::renderHeader("Registrar Ejercicio con Multimedia");
        ?>
        <div class="container">
            <div class="card form-card">
                <div class="card-header">
                    <h2>Registrar Ejercicio Completo con Multimedia</h2>
                    <a href="index.php?c=Ejercicio&a=listarEjerciciosConMultimedia" class="btn btn-secondary">Volver al Catálogo</a>
                </div>
                <div class="card-body">
                    <form action="index.php?c=Ejercicio&a=crearEjercicioCompleto" method="POST" enctype="multipart/form-data" class="standard-form">
                        <div class="form-group">
                            <label for="nombre">Nombre del Ejercicio:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Dominadas con agarre supino" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción de Ejecución:</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Instrucciones biomecánicas, postura y respiración..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label><strong>Imagen Ilustrativa:</strong></label>
                            <label for="archivo_imagen" style="font-weight: normal; margin-top: 4px;">Seleccionar imagen desde tu dispositivo:</label>
                            <input type="file" name="archivo_imagen" id="archivo_imagen" class="form-control" accept="image/*">
                            <small class="form-text text-muted" style="display:block; margin: 4px 0;">O escribe una URL de internet si no subes archivo:</small>
                            <input type="text" name="url_iamgen" id="url_iamgen" class="form-control" placeholder="https://ejemplo.com/imagen.jpg">
                        </div>

                        <div class="form-group">
                            <label><strong>Video Demostrativo (Opcional):</strong></label>
                            <label for="archivo_video" style="font-weight: normal; margin-top: 4px;">Seleccionar video desde tu dispositivo:</label>
                            <input type="file" name="archivo_video" id="archivo_video" class="form-control" accept="video/mp4,video/webm">
                            <small class="form-text text-muted" style="display:block; margin: 4px 0;">O escribe una URL de internet si no subes archivo:</small>
                            <input type="text" name="url_video" id="url_video" class="form-control" placeholder="https://ejemplo.com/video.mp4">
                        </div>

                        <button type="submit" class="btn btn-success">Guardar Ejercicio Completo</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        Vista_Layout::renderFooter();
    }

    /**
     * Genera el botón de confirmación de eliminación para un ejercicio
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function confirmarEliminacionEjercicio($id) {
        return '<a href="index.php?c=Ejercicio&a=removerEjercicioCompleto&id=' . urlencode($id) . '" 
                   class="btn btn-danger btn-block" 
                   onclick="return confirm(\'¿Está seguro de eliminar este ejercicio y su multimedia?\')">Eliminar Ejercicio</a>';
    }
}
