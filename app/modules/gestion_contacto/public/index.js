let cedula_actual = null;
const FLAGS = [
    { id: 'flag-natural-bs',        key: 'natural-bs' },
    { id: 'flag-natural-divisas',   key: 'natural-divisas' },
    { id: 'flag-natural-bi',        key: 'natural-bi' },
    { id: 'flag-juridica-bs',       key: 'juridica-bs' },
    { id: 'flag-juridica-divisas',  key: 'juridica-divisas' },
    { id: 'flag-juridica-bi',       key: 'juridica-bi'  }
];

$("#form-search").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/gestion_contacto/api/consultar_datos/`,
        data: Form.json( $("#form-search") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Consultar datos', mensaje);
            cedula_actual = null;
            // Boton nueva gestion
            $("#btn-nueva-gestion").removeAttr('cedula');
            // Limpiamos seccion 1
            $("[data=cedula]").attr('value', '');
            $("[data=celular]").attr('value', '');
            $("[data=gerente_banca_persona]").attr('value', '');
            $("[data=nombre]").attr('value', '');
            $("[data=otro_telefono]").attr('value', '');
            $("[data=gerente_juridico]").attr('value', '');
            $("[data=segmento]").attr('value', '');
            $("[data=correo]").attr('value', '');
            $("[data=vpr_juridico]").attr('value', '');
            // Limpiamos seccion 2
            for(let flag of FLAGS) {
                $(`#${ flag.id }`).removeClass('text-success');
                $(`#${ flag.id }`).removeClass('text-warning');
                $(`#${ flag.id }`).removeClass('text-danger');
                $(`#${ flag.id }`).addClass('text-secondary');
            }
            // Limpiamos seccion 3
            actualizar_tabla_gestion([]);
        },
        ok(data) {
            // Principal
            let seccion_1 = data.seccion_1;
            let seccion_2 = data.seccion_2;
            let seccion_3 = data.seccion_3;

            // Boton nueva gestion
            cedula_actual = seccion_1.cedula;
            $("#btn-nueva-gestion").attr('cedula', seccion_1.cedula);
            
            // Seccion 1
            let valor_defecto = "(No aplica)";
            if( seccion_1.celular == null )                  seccion_1.celular = valor_defecto;
            if( seccion_1.gerente_banca_president == null )  seccion_1.gerente_banca_president = valor_defecto;
            if( seccion_1.nombre == null )                   seccion_1.nombre = valor_defecto;
            if( seccion_1.otro_telefono == null )            seccion_1.otro_telefono = valor_defecto;
            if( seccion_1.gerente_juridico == null )         seccion_1.gerente_juridico = valor_defecto;
            if( seccion_1.segmento == null )                 seccion_1.segmento = valor_defecto;
            if( seccion_1.correo == null )                   seccion_1.correo = valor_defecto;
            if( seccion_1.vpr_juridico == null )             seccion_1.vpr_juridico = valor_defecto;
            
            $("[data=cedula]").attr('value', seccion_1.cedula);
            $("[data=celular]").attr('value', seccion_1.celular);
            $("[data=gerente_banca_persona]").attr('value', seccion_1.gerente_banca_president);
            $("[data=nombre]").attr('value', seccion_1.nombre);
            $("[data=otro_telefono]").attr('value', seccion_1.otro_telefono);
            $("[data=gerente_juridico]").attr('value', seccion_1.gerente_juridico);
            $("[data=segmento]").attr('value', seccion_1.segmento);
            $("[data=correo]").attr('value', seccion_1.correo);
            $("[data=vpr_juridico]").attr('value', seccion_1.vpr_juridico);

            // Seccion 2
            for(let flag of FLAGS) {
                $(`#${ flag.id }`).removeClass('text-success');
                $(`#${ flag.id }`).removeClass('text-warning');
                $(`#${ flag.id }`).removeClass('text-danger');

                if( seccion_2[ flag.key ] == '1' )
                    $(`#${ flag.id }`).addClass('text-success');
                if( seccion_2[ flag.key ] == '2' )
                    $(`#${ flag.id }`).addClass('text-warning');
                if( seccion_2[ flag.key ] == '3' )
                    $(`#${ flag.id }`).addClass('text-danger');
                else
                    $(`#${ flag.id }`).addClass('text-secondary');
            }
            
            // Seccion 3
            actualizar_tabla_gestion(seccion_3);
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Nueva gestion
 */
$("#btn-nueva-gestion").on('click', function() {
    if($(this).attr('cedula') == "" || $(this).attr('cedula') == null || $(this).attr('cedula') == undefined) {
        Alerta.warning('Cargar nueva gestion', 'Debe seleccionar la cedula antes.');
    }
    else {
        $("#modal-registro-gestion").modal('show');
    }
});

$("#modal-registro-gestion form").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/Gestion_Contacto/api/registrar_gestion/`,
        data: {
            cedula: cedula_actual,
            ...Form.json( $("#modal-registro-gestion form") )
        },
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Registrar nueva gestión', mensaje);
        },
        ok(data) {
            actualizar_tabla_gestion(data.gestiones);
            $("#modal-registro-gestion").modal('hide');
            Alerta.ok('Registrar nueva gestión', 'Gestión registrada exitosamente.');
            $('#modal-registro-gestion form')[0].reset();
            actualizar_select_estatus_gestion();
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Selectores del modal
 */
function actualizar_select_estatus_gestion() {
    let tipo_gestion_id = $("#tipo_gestion").val();
    let options = $("#estatus_gestion option");
    let selected_id = null;
    for(let option of options) {
        if( $(option).attr('parent_id') == tipo_gestion_id ) {
            $(option).removeAttr('disabled');
            if(selected_id == null) selected_id = $(option).val();
        }
        else {
            $(option).attr('disabled', '');
        }
    }
    $("#estatus_gestion").val(selected_id)
}

actualizar_select_estatus_gestion();
$("#tipo_gestion").on('change', function() {
    actualizar_select_estatus_gestion();
});

/**
 * Tabla de gestion
 */
var vacio_defecto = '<span class="text-muted small">(No aplica)</span>';
var table_gestion = $("#tabla-gestion").DataTable({
    language: DT_SPANISH,
    order: [[ 1, "desc" ]],
    data: [],
    columns: [
        {
            data: 'fecha_asignacion',
            class: 'text-center text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                let date = new Date(d);
                return `${date.getDate() + 1}/${date.getMonth() + 1}/${date.getFullYear()}`;
            }
        },
        {
            data: 'fecha_gestion',
            class: 'text-center text-truncate',
            render: function(d, type, row) {
                let date = new Date(d);
                return `${date.getDate() + 1}/${date.getMonth() + 1}/${date.getFullYear()}`;
            }
        },
        {
            data: 'ejecutivo',
            class: 'text-truncate',
        },
        {
            data: 'tipo_llamada',
            class: 'text-center text-truncate',
        },
        {
            data: 'tipo_gestion',
            class: 'text-center text-truncate',
        },
        {
            data: 'estatus_gestion',
            class: 'text-center text-truncate',
        },
        {
            data: 'comentario',
        },
        {
            data: 'resolucion_comite',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                else return d;
            }
        },
        {
            data: 'fecha_comite',
            class: 'text-center text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                let date = new Date(d);
                return `${date.getDate() + 1}/${date.getMonth() + 1}/${date.getFullYear()}`;
            }
        },
        {
            data: 'membresia_president',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                else return d;
            }
        },
        {
            data: 'fecha_pago',
            class: 'text-center text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                let date = new Date(d);
                return `${date.getDate() + 1}/${date.getMonth() + 1}/${date.getFullYear()}`;
            }
        },
    ],
});
function actualizar_tabla_gestion(data) {
    table_gestion.clear();
    table_gestion.rows.add(data);
    table_gestion.draw();
}