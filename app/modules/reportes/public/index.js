$("[name=ejecutivo_id]").select2();

$("#form-busqueda").on('submit', function(e) {
    e.preventDefault();
    
    AJAX.enviar({
        url: `${BASE_URL}/Reportes/api/consultar_ci/`,
        data: Form.json( $("#form-busqueda") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Consultar CI', mensaje);
        },
        ok(data) {
            console.log( data );

            $("[data=resp-ejecutivo]").html( `${data.ejecutivo.nombres} ${data.ejecutivo.apellidos}` );
            $("[data=resp-tipo_gestion]").html( data.tipo_gestion );
            $("[data=resp-rango_fecha]").html( data.rango_fecha );

            $("[data=cantidad_gestiones]").html( data.gestiones.length );
            $("[data=tiempo_total_gestiones]").html(data.tiempo_total_gestiones);
            $("[data=tiempo_promedio_gestiones]").html(data.tiempo_promedio_gestiones);

            actualizar_tabla_gestion(data.gestiones);

            $("#collapse-resultado").collapse('show');
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Tabla de gestion
 */
 var vacio_defecto = '<span class="text-muted small">(No aplica)</span>';
 var table_gestion = $("#tabla-gestion").DataTable({
     language: DT_SPANISH,
     order: [[ 0, "desc" ]],
     data: [],
     columns: [
        {
            data: 'fecha_gestion',
            class: 'text-center text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
        {
            data: 'fecha_asignacion',
            class: 'text-center text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
        {
            data: 'ejecutivo',
            class: 'text-left text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
        {
            data: 'tipo_gestion',
            class: 'text-left text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
        {
            data: 'tipo_llamada',
            class: 'text-left text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
        {
            data: 'fecha_cierre',
            class: 'text-center text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
        {
            data: 'estatus_gestion',
            class: 'text-left text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
        {
            data: 'area_resolutoria',
            class: 'text-left text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
        {
            data: 'tiempo_atencion',
            class: 'text-left text-truncate',
            render: function(d, type, row) {
                if(d == null) return vacio_defecto;
                return d;
            }
        },
     ],
 });

function actualizar_tabla_gestion(data) {
    table_gestion.clear();
    table_gestion.rows.add(data);
    table_gestion.draw();
}