<?php include 'header.php'; ?>

<div class="container">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>Gestión de Usuarios</h4>
        <button class="btn btn-primary rounded-3" onclick="abrirModalCrear()">
            <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
        </button>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyUsuarios">
                        <tr><td colspan="8" class="text-center py-4">Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ── MODAL: AGREGAR / EDITAR USUARIO ───────────────────────── -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="modalUsuarioTitulo">
                    <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <form id="frmUsuario">
                    <input type="hidden" id="usu_id">
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="usu_nombre" placeholder="Nombre">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Apellido Paterno <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="usu_paterno" placeholder="Apellido paterno">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Apellido Materno</label>
                            <input type="text" class="form-control rounded-3" id="usu_materno" placeholder="Apellido materno">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Fecha de nacimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control rounded-3" id="usu_fecha_nac">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Sexo</label>
                            <input type="text" class="form-control rounded-3" id="usu_sexo" placeholder="Ej: Masculino, Femenino">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <input type="text" class="form-control rounded-3" id="usu_telefono" placeholder="10 dígitos" maxlength="10">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Correo <span class="text-danger">*</span></label>
                            <input type="email" class="form-control rounded-3" id="usu_correo" placeholder="usuario@correo.com">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Usuario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" id="usu_usuario" placeholder="Nombre de usuario">
                        </div>
                        <div class="col-md-6 mb-3" id="divPassword">
                            <label class="form-label fw-semibold">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control rounded-3" id="usu_password" placeholder="Mínimo 8 caracteres">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3" id="usu_rol">
                                <option value="Cliente">Cliente</option>
                                <option value="Tecnico">Técnico</option>
                                <option value="Admin">Administrador</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Ubicación</label>
                            <input type="text" class="form-control rounded-3" id="usu_ubicacion" placeholder="Ciudad, dirección...">
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-3 px-4" id="btnGuardarUsuario" onclick="guardarUsuario()">
                    <i class="fas fa-save me-2"></i>Guardar
                </button>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="../js/usuarios/usuarios.js"></script>