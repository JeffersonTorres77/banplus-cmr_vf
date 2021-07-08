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
            Alerta.error('Consultar datos', mensaje);
            // Boton nueva gestion
            $("#btn-nueva-gestion").removeAttr('cedula');
            // Limpiamos seccion 1
            $("[data=cedula]").val('');
            $("[data=celular]").val('');
            $("[data=gerente_banca_persona]").val('');
            $("[data=nombre]").val('');
            $("[data=otro_telefono]").val('');
            $("[data=gerente_juridico]").val('');
            $("[data=segmento]").val('');
            $("[data=correo]").val('');
            $("[data=vpr_juridico]").val('');
            // Limpiamos seccion 2
            VINCULACION_NATURAL.dolar.find('td:not(:first-child)').html('');
            VINCULACION_NATURAL.euro.find('td:not(:first-child)').html('');
            VINCULACION_NATURAL.corriente.find('td:not(:first-child)').html('');
            VINCULACION_NATURAL.ahorro.find('td:not(:first-child)').html('');

            VINCULACION_JURIDICA.dolar.find('td:not(:first-child)').html('');
            VINCULACION_JURIDICA.euro.find('td:not(:first-child)').html('');
            VINCULACION_JURIDICA.corriente.find('td:not(:first-child)').html('');
            VINCULACION_JURIDICA.ahorro.find('td:not(:first-child)').html('');
        },
        ok(data) {
            // Principal
            let seccion_1 = data.seccion_1;
            let seccion_2 = data.seccion_2;
            let seccion_3 = data.seccion_3;

            // Boton nueva gestion
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
            
            $("[data=cedula]").val(seccion_1.cedula);
            $("[data=celular]").val(seccion_1.celular);
            $("[data=gerente_banca_persona]").val(seccion_1.gerente_banca_president);
            $("[data=nombre]").val(seccion_1.nombre);
            $("[data=otro_telefono]").val(seccion_1.otro_telefono);
            $("[data=gerente_juridico]").val(seccion_1.gerente_juridico);
            $("[data=segmento]").val(seccion_1.segmento);
            $("[data=correo]").val(seccion_1.correo);
            $("[data=vpr_juridico]").val(seccion_1.vpr_juridico);

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

            console.log( seccion_2 );

            // Seccion 3
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
});