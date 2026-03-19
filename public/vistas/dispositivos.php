<?php include 'header.php'; ?>

<div class="container">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-laptop me-2 text-primary"></i>Gestión de Dispositivos</h4>
        <button class="btn btn-primary rounded-3" onclick="abrirModalCrear()">
            <i class="fas fa-plus me-2"></i>Nuevo Dispositivo
        </button>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaDispositivos">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Color</th>
                            <th>Usuario asignado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyDispositivos">
                        <tr><td colspan="7" class="text-center py-4">Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ── MODAL: AGREGAR / EDITAR DISPOSITIVO ────────────────────── -->
<div class="modal fade" id="modalDispositivo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="modalDispositivoTitulo">
                    <i class="fas fa-laptop me-2"></i>Nuevo Dispositivo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <form id="frmDispositivo">
                    <input type="hidden" id="disp_id">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tipo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="disp_tipo" placeholder="Laptop, PC, Tablet...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Marca <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="disp_marca" placeholder="Dell, HP, Lenovo...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Modelo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="disp_modelo" placeholder="Modelo del dispositivo">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Color</label>
                            <input type="text" class="form-control rounded-3" id="disp_color" placeholder="Negro, Blanco...">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea class="form-control rounded-3" id="disp_descripcion" rows="2" placeholder="Descripción del dispositivo"></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Memoria RAM</label>
                            <input type="text" class="form-control rounded-3" id="disp_memoria" placeholder="8GB, 16GB...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Disco Duro</label>
                            <input type="text" class="form-control rounded-3" id="disp_disco" placeholder="256GB SSD...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Procesador</label>
                            <input type="text" class="form-control rounded-3" id="disp_procesador" placeholder="Intel i5, AMD...">
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-3 px-4" onclick="guardarDispositivo()">
                    <i class="fas fa-save me-2"></i>Guardar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ── MODAL: ASIGNAR USUARIO ─────────────────────────────────── -->
<div class="modal fade" id="modalAsignar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-tag me-2"></i>Asignar / Quitar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <input type="hidden" id="asignar_id_dispositivo">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Usuario actual</label>
                    <input type="text" class="form-control rounded-3 bg-light" id="asignar_usuario_actual" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Buscar usuario por nombre</label>
                    <input type="text" class="form-control rounded-3" id="asignar_buscar" placeholder="Escribe el nombre..." oninput="buscarUsuarios()">
                </div>

                <div id="listaUsuarios" class="list-group mb-3" style="max-height:200px; overflow-y:auto;"></div>

                <input type="hidden" id="asignar_id_usuario">
                <div id="usuarioSeleccionado" class="alert d-none"></div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-danger rounded-3" onclick="quitarUsuario()">
                    <i class="fas fa-user-minus me-2"></i>Quitar usuario
                </button>
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-3 px-4" onclick="asignarUsuario()">
                    <i class="fas fa-user-check me-2"></i>Asignar
                </button>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="../js/dispositivos/dispositivos.js"></script>