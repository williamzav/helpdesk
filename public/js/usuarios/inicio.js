// ==========================================
// JS: inicio.js
// ==========================================

// Espera a que todo el documento cargue (incluyendo jQuery)
window.addEventListener('load', function () {
    cargarDatosSesion();
});

function cargarDatosSesion() {
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

            $('#spanNombre').text(u.nombre + ' ' + u.paterno);
            $('#spanRol').text(u.rol);
            $('#spanUsuario').text(u.usuario);
            $('#spanCorreo').text(u.correo);
            $('#spanTelefono').text(u.telefono || '—');
            $('#spanUbicacion').text(u.ubicacion || '—');
            $('#spanFecha').text(u.fecha_nac || '—');
            $('#spanSexo').text(u.sexo || '—');

            renderTarjetas(u.rol);
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar la información del usuario.'
            });
        }
    });
}

function renderTarjetas(rol) {
    const tarjetas = {
        Admin: [
            { icon: 'fas fa-users',       color: 'primary',   titulo: 'Usuarios',     link: 'usuarios.php',     desc: 'Gestionar usuarios del sistema' },
            { icon: 'fas fa-file-alt',     color: 'success',   titulo: 'Reportes',     link: 'reportes.php',     desc: 'Ver todos los reportes' },
            { icon: 'fas fa-file-invoice', color: 'info',      titulo: 'Mis Reportes', link: 'misreportes.php',  desc: 'Ver mis reportes generados' },
            { icon: 'fas fa-laptop',       color: 'secondary', titulo: 'Dispositivos', link: 'dispositivos.php', desc: 'Gestionar dispositivos' },
            { icon: 'fas fa-user-edit',    color: 'dark',      titulo: 'Editar Perfil',link: 'editar.php',       desc: 'Actualizar mis datos' },
        ],
        Tecnico: [
            { icon: 'fas fa-file-alt',     color: 'success',   titulo: 'Reportes',     link: 'reportes.php',     desc: 'Gestionar reportes técnicos' },
            { icon: 'fas fa-laptop',       color: 'secondary', titulo: 'Dispositivos', link: 'dispositivos.php', desc: 'Gestionar dispositivos' },
            { icon: 'fas fa-user-edit',    color: 'dark',      titulo: 'Editar Perfil',link: 'editar.php',       desc: 'Actualizar mis datos' },
        ],
        Cliente: [
            { icon: 'fas fa-file-invoice', color: 'info',      titulo: 'Mis Reportes', link: 'misreportes.php',  desc: 'Ver y crear mis reportes' },
            { icon: 'fas fa-user-edit',    color: 'dark',      titulo: 'Editar Perfil',link: 'editar.php',       desc: 'Actualizar mis datos' },
        ]
    };

    const lista = tarjetas[rol] || [];
    let html = '';

    lista.forEach(t => {
        html += `
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <a href="${t.link}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-4 h-100 text-center p-3 card-acceso">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="${t.icon} fa-3x text-${t.color}"></i>
                        </div>
                        <h6 class="fw-bold">${t.titulo}</h6>
                        <p class="text-muted small mb-0">${t.desc}</p>
                    </div>
                </div>
            </a>
        </div>`;
    });

    $('#tarjetasAcceso').html(html);
}