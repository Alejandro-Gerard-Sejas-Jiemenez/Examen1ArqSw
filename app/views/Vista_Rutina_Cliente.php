<?php
require_once __DIR__ . '/Vista_Layout.php';

/**
 * Clase Vista_Rutina_Cliente
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Vista
 * ElementID: 70
 */
class Vista_Rutina_Cliente {

    /**
     * Despliega la rutina vigente asignada al cliente
     * Utilizado en CU7: Visualizar Rutina Asignada
     */
    public function desplegarRutinaVigente($rutina = null, $detalles = []) {
        Vista_Layout::renderHeader("Mi Rutina Asignada - Gym Architect");
        ?>
        <div class="container">
            <div class="routine-header-card card">
                <?php if (!$rutina): ?>
                    <div class="empty-state">
                        <h3>No tienes una rutina asignada actualmente</h3>
                        <p>Tu entrenador asignará tu planificación semanal en breve.</p>
                    </div>
                <?php else: ?>
                    <div class="routine-title-box">
                        <span class="badge">Rutina Vigente</span>
                        <h2><?= htmlspecialchars($rutina['nombre']) ?></h2>
                        <p class="meta">
                            <strong>Entrenador:</strong> <?= htmlspecialchars($rutina['nombre_entrenador'] ?? 'N/A') ?> | 
                            <strong>Vigencia:</strong> <?= htmlspecialchars($rutina['fecha_inicio']) ?> al <?= htmlspecialchars($rutina['fecha_fin']) ?>
                        </p>
                        <?php if (!empty($rutina['indicaciones'])): ?>
                            <div class="notes-box">
                                <strong>Indicaciones del Entrenador:</strong>
                                <p><?= nl2br(htmlspecialchars($rutina['indicaciones'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h3 class="section-title mt-4">Planificación de Ejercicios</h3>
                    <div class="exercise-list">
                        <?php foreach ($detalles as $d): ?>
                            <div class="card exercise-row-card" onclick="seleccionarEjercicio('<?= $d['id'] ?>')">
                                <div class="exercise-thumb" style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                                    <?php 
                                    $imgs = !empty($d['todas_imagenes']) ? $d['todas_imagenes'] : (!empty($d['imagen_url']) ? [$d['imagen_url']] : []);
                                    ?>
                                    <?php if (!empty($imgs)): ?>
                                        <a href="<?= htmlspecialchars($imgs[0]) ?>" target="_blank" id="link_img_<?= $d['id'] ?>" title="Abrir imagen en tamaño completo" style="display: block;">
                                            <img src="<?= htmlspecialchars($imgs[0]) ?>" alt="Ejercicio" id="cli_img_<?= $d['id'] ?>">
                                        </a>
                                        <?php if (count($imgs) > 1): ?>
                                            <div style="display: flex; gap: 4px; justify-content: center; margin-top: 4px;">
                                                <?php foreach ($imgs as $idx => $iSrc): ?>
                                                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #0284c7; cursor: pointer; border: 1px solid #fff;" 
                                                          title="Ver foto <?= $idx + 1 ?>"
                                                          onclick="event.stopPropagation(); document.getElementById('cli_img_<?= $d['id'] ?>').src = '<?= htmlspecialchars(addslashes($iSrc)) ?>'; document.getElementById('link_img_<?= $d['id'] ?>').href = '<?= htmlspecialchars(addslashes($iSrc)) ?>';"></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="placeholder-thumb">Sin imagen</div>
                                    <?php endif; ?>
                                </div>
                                <div class="exercise-info">
                                    <h4><?= htmlspecialchars($d['nombre_ejercicio']) ?></h4>
                                    <p><?= htmlspecialchars($d['descripcion_ejercicio']) ?></p>
                                    <div class="metrics-tags">
                                        <span class="tag">Series: <strong><?= htmlspecialchars($d['series']) ?></strong></span>
                                        <span class="tag">Reps: <strong><?= htmlspecialchars($d['repeticiones']) ?></strong></span>
                                        <span class="tag">Descanso: <strong><?= htmlspecialchars($d['tiempo'] ?? '60 seg') ?></strong></span>
                                    </div>
                                </div>
                                <div class="exercise-actions">
                                    <?php if (!empty($d['video_url'])): ?>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="verVideo('<?= htmlspecialchars($d['video_url']) ?>', '<?= htmlspecialchars(addslashes($d['nombre_ejercicio'])) ?>')">
                                            Ver Video Demostrativo
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?= $this->seleccionarEjercicioDetalle() ?>
        <?= $this->reproducirVideoApoyo() ?>
        <?php
        Vista_Layout::renderFooter();
    }

    /**
     * Permite al cliente seleccionar un detalle específico de ejercicio
     * Utilizado en CU7: Visualizar Rutina Asignada
     */
    public function seleccionarEjercicioDetalle() {
        ob_start();
        ?>
        <script>
            function seleccionarEjercicio(detalleId) {
                console.log("Ejercicio seleccionado:", detalleId);
            }
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Modal interactivo para reproducir el video de apoyo del ejercicio
     * Utilizado en CU7: Visualizar Rutina Asignada
     */
    public function reproducirVideoApoyo($videoUrl = null) {
        ob_start();
        ?>
        <div id="modalVideo" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="videoTitulo">Video Demostrativo de Apoyo</h3>
                    <button type="button" class="close-btn" onclick="cerrarModalVideo()">&times;</button>
                </div>
                <div class="modal-body">
                    <video id="playerVideo" controls class="player-video-responsive">
                        <source id="videoSource" src="" type="video/mp4">
                        Tu navegador no soporta el formato de video.
                    </video>
                    <iframe id="iframeVideo" class="player-video-responsive" style="display:none; width:100%; height:350px; border:none; border-radius:8px;" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                </div>
            </div>
        </div>
        <script>
            function verVideo(url, nombre) {
                const modal = document.getElementById('modalVideo');
                const player = document.getElementById('playerVideo');
                const iframe = document.getElementById('iframeVideo');
                const source = document.getElementById('videoSource');
                const titulo = document.getElementById('videoTitulo');
                
                titulo.textContent = 'Demostración: ' + nombre;

                const ytMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|shorts\/|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/);
                if (ytMatch && ytMatch[1]) {
                    player.pause();
                    player.style.display = 'none';
                    iframe.style.display = 'block';
                    iframe.src = 'https://www.youtube.com/embed/' + ytMatch[1];
                } else {
                    iframe.src = '';
                    iframe.style.display = 'none';
                    player.style.display = 'block';
                    source.src = url;
                    player.load();
                    player.play().catch(e => console.log(e));
                }
                modal.classList.add('is-visible');
            }

            function cerrarModalVideo() {
                const modal = document.getElementById('modalVideo');
                const player = document.getElementById('playerVideo');
                const iframe = document.getElementById('iframeVideo');
                player.pause();
                iframe.src = '';
                modal.classList.remove('is-visible');
            }
        </script>
        <?php
        return ob_get_clean();
    }
}
