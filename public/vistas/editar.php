<?php include 'header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Datos personales -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-primary text-white rounded-top-4 py-3">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar datos personales</h5>
                </div>
                <div class="card-body p-4">
                    <form id="frmEditar">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label for="edit_nombre" class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" id="edit_nombre" placeholder="Nombre">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_paterno" class="form-label fw-semibold">Apellido Paterno <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" id="edit_paterno" placeholder="Apellido paterno">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_materno" class="form-label fw-semibold">Apellido Materno</label>
                                <input type="text" class="form-control rounded-3" id="edit_materno" placeholder="Apellido materno">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_fecha_nac" class="form-label fw-semibold">Fecha de nacimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control rounded-3" id="edit_fecha_nac">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_sexo" class="form-label fw-semibold">Sexo</label>
                                <input type="text" class="form-control rounded-3" id="edit_sexo" placeholder="Ej: Masculino, Femenino">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_telefono" class="form-label fw-semibold">Teléfono</label>
                                <input type="text" class="form-control rounded-3" id="edit_telefono" placeholder="10 dígitos" maxlength="10">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_correo" class="form-label fw-semibold">Correo electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control rounded-3" id="edit_correo" placeholder="usuario@correo.com">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_usuario" class="form-label fw-semibold">Usuario <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" id="edit_usuario" placeholder="Nombre de usuario">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Rol</label>
                                <input type="text" class="form-control rounded-3 bg-light" id="edit_rol" readonly>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="edit_ubicacion" class="form-label fw-semibold">Ubicación</label>
                                <input type="text" class="form-control rounded-3" id="edit_ubicacion" placeholder="Ciudad, dirección...">
                            </div>

                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary rounded-3 px-4" onclick="guardarPerfil()">
                                <i class="fas fa-save me-2"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cambiar contraseña -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-warning text-dark rounded-top-4 py-3">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Cambiar contraseña</h5>
                </div>
                <div class="card-body p-4">
                    <form id="frmPassword">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label for="pass_actual" class="form-label fw-semibold">Contraseña actual <span class="text-danger">*</span></label>
                                <input type="password" class="form-control rounded-3" id="pass_actual" placeholder="Ingresa tu contraseña actual">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pass_nueva" class="form-label fw-semibold">Nueva contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control rounded-3" id="pass_nueva" placeholder="Mínimo 8 caracteres">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pass_confirma" class="form-label fw-semibold">Confirmar contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control rounded-3" id="pass_confirma" placeholder="Repite la nueva contraseña">
                            </div>

                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-warning rounded-3 px-4" onclick="cambiarPassword()">
                                <i class="fas fa-key me-2"></i>Actualizar contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="../js/usuarios/editar.js"></script>