<?php

class LDAP
{
    private $conexion;
    private $servidor;
    private $puerto;
    private $DN;

    public function __construct() {
        $this->servidor = config('LDAP.HOST');
        $this->puerto = config('LDAP.PUERTO');
        $this->DN = config('LDAP.DN');

        //Conectamos al servidor
        $this->conexion = ldap_connect($this->servidor, $this->puerto);
        ldap_set_option($this->conexion, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->conexion, LDAP_OPT_REFERRALS, 0);

        //Verificamos que haya conexión
        if(!$this->conexion)
            throw new Exception("Ocurrio un error al intentar conectarse al Directorio Activo.");
    }

    public function conectar($user, $pass) {
        set_error_handler("Handler::nulo");
        $salida = FALSE;
        if(@ldap_bind($this->conexion, $user."@".$this->servidor, $pass)) $salida = TRUE;
        set_error_handler("Handler::error");
        return $salida;
    }

    public function consultar_usuario($user) {
        $filtro = "(&(samaccountname=".$user."))";
        $respuesta = ldap_search($this->conexion, $this->DN, $filtro);
        if(!$respuesta) return NULL;

        $entradas = ldap_get_entries($this->conexion, $respuesta);
        if ($entradas['count'] <= 0) return NULL;

        $datos['usuario']           = (isset($entradas[0]['samaccountname'][0])) ? $entradas[0]['samaccountname'][0] : '';
        $datos['cn']                = (isset($entradas[0]['cn'][0])) ? $entradas[0]['cn'][0] : '';
        $datos['nombres']           = (isset($entradas[0]['givenname'][0])) ? $entradas[0]['givenname'][0] : '';
        $datos['apellidos']         = (isset($entradas[0]['sn'][0])) ? $entradas[0]['sn'][0] : '';
        $datos['correo']            = (isset($entradas[0]['mail'][0])) ? $entradas[0]['mail'][0] : '';
        $datos['cargo']             = (isset($entradas[0]['title'][0])) ? $entradas[0]['title'][0] : '';
        $datos['departamento']      = (isset($entradas[0]['department'][0])) ? $entradas[0]['department'][0] : '';
        $datos['direccion_oficina'] = (isset($entradas[0]['physicaldeliveryofficename'][0])) ? $entradas[0]['physicaldeliveryofficename'][0] : '';

        return $datos;
    }
}