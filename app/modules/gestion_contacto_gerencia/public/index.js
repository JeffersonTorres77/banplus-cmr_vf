let cedula_actual = null;
const VINCULACION_NATURAL = {
    mes_1:      $("#vinculacion-natural .mes_1"),
    mes_2:      $("#vinculacion-natural .mes_2"),
    mes_3:      $("#vinculacion-natural .mes_3"),
    dolar:      $("#vinculacion-natural .dolar"),
    euro:       $("#vinculacion-natural .euro"),
    corriente:  $("#vinculacion-natural .corriente"),
    ahorro:     $("#vinculacion-natural .ahorro"),
};
const VINCULACION_JURIDICA = {
    mes_1:      $("#vinculacion-juridica .mes_1"),
    mes_2:      $("#vinculacion-juridica .mes_2"),
    mes_3:      $("#vinculacion-juridica .mes_3"),
    dolar:      $("#vinculacion-juridica .dolar"),
    euro:       $("#vinculacion-juridica .euro"),
    corriente:  $("#vinculacion-juridica .corriente"),
    ahorro:     $("#vinculacion-juridica .ahorro"),
};

$("#form-search").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/gestion_contacto_gerencia/api/consultar_datos/`,
        data: Form.json( $("#form-search") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            cedula_actual = null;
            Alerta.error('Consultar datos', mensaje);
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
            VINCULACION_NATURAL.dolar.find('td:not(:first-child)').html('');
            VINCULACION_NATURAL.euro.find('td:not(:first-child)').html('');
            VINCULACION_NATURAL.corriente.find('td:not(:first-child)').html('');
            VINCULACION_NATURAL.ahorro.find('td:not(:first-child)').html('');

            VINCULACION_JURIDICA.dolar.find('td:not(:first-child)').html('');
            VINCULACION_JURIDICA.euro.find('td:not(:first-child)').html('');
            VINCULACION_JURIDICA.corriente.find('td:not(:first-child)').html('');
            VINCULACION_JURIDICA.ahorro.find('td:not(:first-child)').html('');
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
            VINCULACION_NATURAL.mes_1.html( seccion_2.mes_1 );
            VINCULACION_NATURAL.mes_2.html( seccion_2.mes_2 );
            VINCULACION_NATURAL.mes_3.html( seccion_2.mes_3 );

            $( VINCULACION_NATURAL.dolar.find('td')[1] ).html( formatNumber(seccion_2.vinculacion_natural.dolar.mes_1) );
            $( VINCULACION_NATURAL.dolar.find('td')[2] ).html( formatNumber(seccion_2.vinculacion_natural.dolar.mes_2) );
            $( VINCULACION_NATURAL.dolar.find('td')[3] ).html( formatNumber(seccion_2.vinculacion_natural.dolar.mes_3) );
            $( VINCULACION_NATURAL.dolar.find('td')[4] ).html( formatNumber(seccion_2.vinculacion_natural.dolar.promedio) );

            $( VINCULACION_NATURAL.euro.find('td')[1] ).html( formatNumber(seccion_2.vinculacion_natural.euro.mes_1) );
            $( VINCULACION_NATURAL.euro.find('td')[2] ).html( formatNumber(seccion_2.vinculacion_natural.euro.mes_2) );
            $( VINCULACION_NATURAL.euro.find('td')[3] ).html( formatNumber(seccion_2.vinculacion_natural.euro.mes_3) );
            $( VINCULACION_NATURAL.euro.find('td')[4] ).html( formatNumber(seccion_2.vinculacion_natural.euro.promedio) );

            $( VINCULACION_NATURAL.corriente.find('td')[1] ).html( formatNumber(seccion_2.vinculacion_natural.corriente.mes_1) );
            $( VINCULACION_NATURAL.corriente.find('td')[2] ).html( formatNumber(seccion_2.vinculacion_natural.corriente.mes_2) );
            $( VINCULACION_NATURAL.corriente.find('td')[3] ).html( formatNumber(seccion_2.vinculacion_natural.corriente.mes_3) );
            $( VINCULACION_NATURAL.corriente.find('td')[4] ).html( formatNumber(seccion_2.vinculacion_natural.corriente.promedio) );

            $( VINCULACION_NATURAL.ahorro.find('td')[1] ).html( formatNumber(seccion_2.vinculacion_natural.ahorro.mes_1) );
            $( VINCULACION_NATURAL.ahorro.find('td')[2] ).html( formatNumber(seccion_2.vinculacion_natural.ahorro.mes_2) );
            $( VINCULACION_NATURAL.ahorro.find('td')[3] ).html( formatNumber(seccion_2.vinculacion_natural.ahorro.mes_3) );
            $( VINCULACION_NATURAL.ahorro.find('td')[4] ).html( formatNumber(seccion_2.vinculacion_natural.ahorro.promedio) );

            VINCULACION_JURIDICA.mes_1.html( seccion_2.mes_1 );
            VINCULACION_JURIDICA.mes_2.html( seccion_2.mes_2 );
            VINCULACION_JURIDICA.mes_3.html( seccion_2.mes_3 );

            $( VINCULACION_JURIDICA.dolar.find('td')[1] ).html( formatNumber(seccion_2.vinculacion_juridica.dolar.mes_1) );
            $( VINCULACION_JURIDICA.dolar.find('td')[2] ).html( formatNumber(seccion_2.vinculacion_juridica.dolar.mes_2) );
            $( VINCULACION_JURIDICA.dolar.find('td')[3] ).html( formatNumber(seccion_2.vinculacion_juridica.dolar.mes_3) );
            $( VINCULACION_JURIDICA.dolar.find('td')[4] ).html( formatNumber(seccion_2.vinculacion_juridica.dolar.promedio) );

            $( VINCULACION_JURIDICA.euro.find('td')[1] ).html( formatNumber(seccion_2.vinculacion_juridica.euro.mes_1) );
            $( VINCULACION_JURIDICA.euro.find('td')[2] ).html( formatNumber(seccion_2.vinculacion_juridica.euro.mes_2) );
            $( VINCULACION_JURIDICA.euro.find('td')[3] ).html( formatNumber(seccion_2.vinculacion_juridica.euro.mes_3) );
            $( VINCULACION_JURIDICA.euro.find('td')[4] ).html( formatNumber(seccion_2.vinculacion_juridica.euro.promedio) );

            $( VINCULACION_JURIDICA.corriente.find('td')[1] ).html( formatNumber(seccion_2.vinculacion_juridica.corriente.mes_1) );
            $( VINCULACION_JURIDICA.corriente.find('td')[2] ).html( formatNumber(seccion_2.vinculacion_juridica.corriente.mes_2) );
            $( VINCULACION_JURIDICA.corriente.find('td')[3] ).html( formatNumber(seccion_2.vinculacion_juridica.corriente.mes_3) );
            $( VINCULACION_JURIDICA.corriente.find('td')[4] ).html( formatNumber(seccion_2.vinculacion_juridica.corriente.promedio) );
            
            $( VINCULACION_JURIDICA.ahorro.find('td')[1] ).html( formatNumber(seccion_2.vinculacion_juridica.ahorro.mes_1) );
            $( VINCULACION_JURIDICA.ahorro.find('td')[2] ).html( formatNumber(seccion_2.vinculacion_juridica.ahorro.mes_2) );
            $( VINCULACION_JURIDICA.ahorro.find('td')[3] ).html( formatNumber(seccion_2.vinculacion_juridica.ahorro.mes_3) );
            $( VINCULACION_JURIDICA.ahorro.find('td')[4] ).html( formatNumber(seccion_2.vinculacion_juridica.ahorro.promedio) );

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
        url: `${BASE_URL}/Gestion_Contacto_Gerencia/api/registrar_gestion/`,
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