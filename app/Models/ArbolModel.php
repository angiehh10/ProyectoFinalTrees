<?php

namespace App\Models;

use CodeIgniter\Model;

class ArbolModel extends Model
{
    protected $table = 'arboles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['especie_id', 'ubicacion_geografica', 'estado', 'precio', 'tamano', 'foto'];

    public function crearArbol($data)
    {
        return $this->insert($data);
    }

    public function actualizarArbol($id, $data)
    {
        return $this->update($id, $data);
    }

    public function obtenerArboles()
    {
        return $this->findAll();
    }

    public function obtenerArbolesDisponibles()
    {
        return $this->where('estado', 'Disponible')->findAll();
    }

    public function obtenerArbolPorId($id)
    {
        return $this->find($id);
    }

    public function obtenerArbolesParaActualizacion()
    {
        return $this->select('id, ubicacion_geografica')->findAll();
    }


    public function countArbolesDisponibles()
    {
        return $this->where('estado', 'Disponible')->countAllResults();
    }

    public function countArbolesVendidos()
    {
        return $this->where('estado', 'Vendido')->countAllResults();
    }
}
