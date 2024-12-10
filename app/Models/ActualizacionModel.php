<?php

namespace App\Models;

use CodeIgniter\Model;

class ActualizacionModel extends Model
{
    protected $table = 'actualizaciones';
    protected $primaryKey = 'id';
    protected $allowedFields = ['arbol_id', 'tamano', 'estado', 'fecha_actualizacion', 'foto']; // Incluye la columna foto

    public function registrarActualizacion($data)
    {
        return $this->insert($data); // Inserta un nuevo registro
    }

    public function obtenerActualizacionesPorArbol($arbol_id)
    {
        return $this->where('arbol_id', $arbol_id)->findAll(); // Obtiene todas las actualizaciones de un árbol
    }
}

