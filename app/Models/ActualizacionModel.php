<?php

namespace App\Models;

use CodeIgniter\Model;

class ActualizacionModel extends Model
{
    protected $table = 'actualizaciones';
    protected $primaryKey = 'id';
    protected $allowedFields = ['arbol_id', 'tamano', 'estado', 'fecha_actualizacion'];

    public function registrarActualizacion($data)
    {
        return $this->insert($data);
    }

    public function obtenerActualizacionesPorArbol($arbol_id)
    {
        return $this->where('arbol_id', $arbol_id)->findAll();
    }
}
