// ==========================================
// JS: editar.js
// Precargar datos y guardar cambios
// ==========================================

window.addEventListener('load', function () {
    cargarDatos();
});

// ── PRECARGAR DATOS ───────────────────────────────────────────
function cargarDatos() {
    $.ajax({
        url: '../../controller/UsuarioController.php',
        method: 'POST',
        data: { action: 'get_sesion' },
        dataType: 'json',
        success: function (res) {
            if (!res.success) {
                window.location.href = '../../index.html';
                return;
            }
            const u = res.data;
            $('#edit_nombre').val(u.nombre);
            $('#edit_paterno').val(u.paterno);
            $('#edit_materno').val(u.materno);
            $('#edit_fecha_nac').val(u.fecha_nac);
            $('#edit_sexo').val(u.sexo);
            $('#edit_telefono').val(u.telefono);
            $('#edit_correo').val(u.correo);
            $('#edit_usuario').val(u.usuario);
            $('#edit_rol').val(u.rol);
            $('#edit_ubicacion').val(u.ubicacion);
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar la información.' });
        }
    });
}

// ── GUARDAR DATOS PERSONALES ──────────────────────────────────
function guardarPerfil() {
    const nombre    = $('#edit_nombre').val().trim();
    const paterno   = $('#edit_paterno').val().trim();
    const materno   = $('#edit_materno').val().trim();
    const fecha_nac = $('#edit_fecha_nac').val().trim();
    const sexo      = $('#edit_sexo').val().trim();
    const telefono  = $('#edit_telefono').val().trim();
    const correo    = $('#edit_correo').val().trim();
    const usuario   = $('#edit_usuario').val().trim();
    const ubicacion = $('#edit_ubicacion').val().trim();

    // ── Validaciones ──────────────────────────────────────────
    if (!nombre || !paterno || !correo || !usuario || !fecha_nac) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            html: 'Los campos <b>Nombre, Apellido Paterno, Fecha de nacimiento, Correo y Usuario</b> son obligatorios.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexCorreo.test(correo)) {
        Swal.fire({
            icon: 'warning',
            title: 'Correo inválido',
            text: 'Por favor ingresa un correo electrónico válido.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    if (telefono && !/^\d{10}$/.test(telefono)) {
        Swal.fire({
            icon: 'warning',
            title: 'Teléfono inválido',
            text: 'El teléfono debe contener exactamente 10 dígitos numéricos.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    const btn = $('button[onclick="guardarPerfil()"]');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');

    $.ajax({
        url: '../../controller/UsuarioController.php',
        method: 'POST',
        data: {
            action:    'editar_perfil',
            nombre:    nombre,
            paterno:   paterno,
            materno:   materno,
            fecha_nac: fecha_nac,
            sexo:      sexo,
            telefono:  telefono,
            correo:    correo,
            usuario:   usuario,
            ubicacion: ubicacion
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Datos actualizados!',
                    text: 'Tu información ha sido guardada correctamente.',
                    confirmButtonColor: '#0d6efd',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
            }
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar cambios');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.', confirmButtonColor: '#0d6efd' });
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar cambios');
        }
    });
}

// ── CAMBIAR CONTRASEÑA ────────────────────────────────────────
function cambiarPassword() {
    const actual   = $('#pass_actual').val().trim();
    const nueva    = $('#pass_nueva').val().trim();
    const confirma = $('#pass_confirma').val().trim();

    // ── Validaciones ──────────────────────────────────────────
    if (!actual || !nueva || !confirma) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Todos los campos de contraseña son obligatorios.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    if (nueva.length < 8) {
        Swal.fire({
            icon: 'warning',
            title: 'Contraseña muy corta',
            text: 'La nueva contraseña debe tener al menos 8 caracteres.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    if (nueva !== confirma) {
        Swal.fire({
            icon: 'warning',
            title: 'Las contraseñas no coinciden',
            text: 'La nueva contraseña y la confirmación deben ser iguales.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    const btn = $('button[onclick="cambiarPassword()"]');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...');

    $.ajax({
        url: '../../controller/UsuarioController.php',
        method: 'POST',
        data: {
            action:            'cambiar_password',
            password_actual:   actual,
            password_nueva:    nueva,
            password_confirma: confirma
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#frmPassword')[0].reset();
                Swal.fire({
                    icon: 'success',
                    title: '¡Contraseña actualizada!',
                    text: 'Tu contraseña ha sido cambiada correctamente.',
                    confirmButtonColor: '#0d6efd',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
            }
            btn.prop('disabled', false).html('<i class="fas fa-key me-2"></i>Actualizar contraseña');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.', confirmButtonColor: '#0d6efd' });
            btn.prop('disabled', false).html('<i class="fas fa-key me-2"></i>Actualizar contraseña');
        }
    });
}