<?php

class Rol extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'roles';

    public function esValido($slug) {
        $rows = Permiso::select('permisos.slug')
            ->where('permisos.slug', $slug)
            ->where('permisos_roles.rol_id', $this->id)
            ->join('permisos_roles', 'permisos.id', '=', 'permisos_roles.permiso_id')
            ->count();

        // echo json_encode($rows); exit;
        return ($rows > 0);
    }

    public function cambiarPermiso($permiso_id, $permitido) {
        if($permitido) {
            $pr = new Permiso_Rol;
            $pr->rol_id = $this->id;
            $pr->permiso_id = $permiso_id;
            $pr->save();
        }
        else {
            $pr = Permiso_Rol::where('rol_id', $this->id)->where('permiso_id', $permiso_id)->delete();
        }
    }
}