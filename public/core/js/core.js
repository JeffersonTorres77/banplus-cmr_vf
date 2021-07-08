/**
 * Alertas
 */
const Alerta = {
    autohide: true,
    delay: 5000,

    default(title, content) {
        $(document).Toasts('create', {
            autohide: Alerta.autohide,
            delay: Alerta.delay,
            title: title,
            body: content
        })
    },

    ok(title, content) {
        $(document).Toasts('create', {
            autohide: Alerta.autohide,
            delay: Alerta.delay,
            class: 'bg-success',
            title: title,
            body: content
        });
    },

    error(title, content) {
        $(document).Toasts('create', {
            autohide: Alerta.autohide,
            delay: Alerta.delay,
            class: 'bg-danger',
            title: title,
            body: content
        });
    },

    warning(title, content) {
        $(document).Toasts('create', {
            autohide: Alerta.autohide,
            delay: Alerta.delay,
            class: 'bg-warning',
            title: title,
            body: content
        });
    }
};

/**
 * AJAX
 */
const AJAX = {
    enviar(options) {
        // Validacion
        if(options.url == undefined) throw `No se ha enviado el atributo 'url'.`;

        // Valores por defecto
        if(options.data == undefined) options.data = {};
        if(options.method == undefined) options.method = 'POST';
        if(options.antes == undefined) options.antes = () => {
            Loader.show();
        };
        if(options.error == undefined) options.error = (mensaje) => {
            Loader.hide();
            Alerta.error('Mensaje del sistema', mensaje);
        }
        if(options.ok == undefined) options.ok = (data) => {
            Loader.hide();
            console.log(data);
        };
        if(options.final == undefined) options.final = () => {
            /* Nothing */
        }

        // Envio
        $.ajax({
            url: options.url,
            type: options.method,
            data: JSON.stringify( options.data ),
            processData: false,
            contentType: 'application/json',
            beforeSend: function() {
                options.antes();
            }
        })
        .done(function(data) {
            if(data.status == undefined) throw "No se recibio el parametro 'status'.";
            if(data.body == undefined) throw "No se recibio el parametro data.body'.";

            if(data.status.toLowerCase() !== 'ok') {
                if(AUDITAR) console.warn(data.body);
                options.error(data.body.message);
            }
            else {
                options.ok(data.body);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error(errorThrown);
        })
        .done(function() {
            options.final();
        });
    }
};

/**
 * Modal loading
 */
const Loader = {
    show() {
        $('body').append(`<div class="modal" id="modal-loader" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4 d-flex justify-content-center align-items-center">
                        <div class="spinner-grow text-dark" role="status"></div>
                    </div>
                </div>
            </div>
        </div>`);

        $("#modal-loader").modal('show');
    },
    hide() {
        $("#modal-loader").modal('hide');
        $("#modal-loader").on('hidden.bs.modal', function() {
            $("#modal-loader").remove();
        });
    }
};

/**
 * Form
 */
const Form = {
    json(jqueryForm) {
        let elements = jqueryForm[0].elements;
        let output = {};

        for(let element of elements) {
            if(element.name == "" || element.name == undefined) continue;
            switch( element.type )
            {
                case 'radio':
                    output[element.name] = element.checked;
                    break;
                case 'checkbox':
                    output[element.name] = element.checked;
                    break;
                default:
                    output[element.name] = element.value;
                    break;
            }
        }
        
        return output;
    }
};

/**
 * Cerrar Sesion
*/
function cerrar_sesion() {
    AJAX.enviar({
        url: `${BASE_URL}/cerrar_sesion`,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Loader.hide();
            Alerta.error('Cerrar Sesión', mensaje);
        },
        ok() {
            location.href = `${BASE_URL}/Login/`;
        }
    });
}

/**
 * Variables por defecto
 */
const DT_SPANISH = {
	"sProcessing":     "Procesando...",
	"sLengthMenu":     "Mostrar _MENU_ registros",
	"sZeroRecords":    "No se encontraron resultados",
	"sEmptyTable":     "Ningún dato disponible en esta tabla",
	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
	"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	"sInfoPostFix":    "",
	"sSearch":         "Buscar:",
	"sUrl":            "",
	"sInfoThousands":  ",",
	"sLoadingRecords": "Cargando...",
	"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Último",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
	},
	"oAria": {
		"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
		"sSortDescending": ": Activar para ordenar la columna de manera descendente"
	}
};

/**
 * Push Menu
 */
 $(document).on('shown.lte.pushmenu', function() {
    $.cookie('sidebar_collapse', 0);
});

$(document).on('collapsed.lte.pushmenu', function() {
    $.cookie('sidebar_collapse', 1);
});


/**
 * Format Number
 */
function formatNumber(num, n = 2, s = '.', c = ',') {
    num = Number(num);
	var re = '\\d(?=(\\d{' + (3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = num.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
}