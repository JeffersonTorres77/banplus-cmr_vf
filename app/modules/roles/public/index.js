/**
 * Tabla
 */
var tabla = $("#table-roles").DataTable({
    language: DT_SPANISH,
    processing: true,
    serverSide: true,
    ajax: {
        url: `${BASE_URL}/Roles/API/datatable/`
    },
    columns: [
        {
            data: "id",
            class: "align-middle text-center",
            width: "50px"
        },
        {
            data: "nombre",
            class: "align-middle text-truncate",
            width: "auto"
        },
        {
            data: "cant_usuarios",
            class: "align-middle text-center",
            width: "50px",
            render: function(d, type, row) {
                return `<div class="badge badge-${ (d <= 0) ? 'warning' : 'info' }">${d}</div>`;
            }
        },
        {
            data: "id",
            width: '70px',
            class: "align-middle text-truncate",
            orderable: false,
            render: function(d, type, row) {
                return `<div class="text-center">
                    <button class="btn btn-outline-success btn-sm btn-opc editar">
                        <i class="fas fa-edit fa-sm"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm btn-opc permisos">
                        <i class="fas fa-key fa-sm"></i>
                    </button>
                </div>`;
            }
        }
    ]
});



/**
 * Modificar
 */
$("#table-roles tbody").on('click', 'button.editar', function() {
    let data = tabla.row( $(this).parents('tr') ).data();

    $("[data-key=nombre]").html(data.nombre);

    $("[value-key=nombre]").val(data.nombre);
    $("[value-key=id]").val(data.id);

    $("#modal-editar").modal('show');
});

$("#modal-editar form").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/roles/api/modificar/`,
        data: Form.json( $("#modal-editar form") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Modificar usuario', mensaje);
        },
        ok(data) {
            $("#modal-editar").modal('hide');
            Alerta.ok('Modificar usuario', 'Usuario modificado exitosamente.');
            tabla.ajax.reload();
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Permisos
 */
$("#table-roles tbody").on('click', 'button.permisos', function() {
    let rol = tabla.row( $(this).parents('tr') ).data();
    $("[data-key=nombre]").html(rol.nombre);
    
    AJAX.enviar({
        url: `${BASE_URL}/Roles/API/Permisos/`,
        data: {
            id: rol.id
        },
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Permisos del rol', mensaje);
        },
        ok(data) {
            render_modal_permisos(data.rol, data.permisos);
            $("#modal-permisos").modal('show');
        },
        final() {
            Loader.hide();
        }
    });
});

$("#table-modificar-permisos tbody").on('submit', 'form', function(e) {
    e.preventDefault();
    let form = Form.json( $(this) );
    AJAX.enviar({
        url: `${BASE_URL}/Roles/API/cambiar-permiso/`,
        data: form,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Cambiar permiso', mensaje);
        },
        ok(data) {
            render_modal_permisos(data.rol, data.permisos);
            $("#modal-permisos").modal('show');
            Alerta.ok('Cambiar permiso', 'Permiso cambiando exitosamente.');
        },
        final() {
            Loader.hide();
        }
    });

    console.log( Form.json( $(this) ) );
});

function render_modal_permisos(rol, permisos) {
    let code = '';
    for(let permiso of permisos) {
        code += `<tr>
            <td class="text-left align-middle">${permiso.description}</td>
            <td class="text-center align-middle">
                <span class="badge badge-${(permiso.permitido) ? 'success' : 'danger'}" style="width: 25px;">
                    ${(permiso.permitido) ? 'Si' : 'No'}
                </span>
            </td>
            <td class="text-center align-middle">
                <form>
                    <input type="hidden" name="rol_id" value="${rol.id}" />
                    <input type="hidden" name="slug_permiso" value="${permiso.slug}" />
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-sync-alt fa-sm"></i>
                    </button>
                </form>
            </td>
        </tr>`;
    }
    $("#table-modificar-permisos tbody").html( code );
}