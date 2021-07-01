<?php

class Usuario extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'usuarios';

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}