<?php

namespace App\Models;

use CodeIgniter\Model;

class AmigoArbolModel extends Model
{
    protected $table = 'amigo_arbol';
    protected $primaryKey = 'id';
    protected $allowedFields = ['amigo_id', 'arbol_id'];

    /**
     * Registrar una compra
     */
    public function registrarCompra($amigo_id, $arbol_id)
    {
        return $this->insert(['amigo_id' => $amigo_id, 'arbol_id' => $arbol_id]);
    }

    /**
     * Obtener árboles por amigo
     */
    public function obtenerArbolesPorAmigo($amigo_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('arboles.*, especies.nombre_comercial AS especie');
        $builder->join('arboles', 'amigo_arbol.arbol_id = arboles.id');
        $builder->join('especies', 'arboles.especie_id = especies.id');
        $builder->where('amigo_arbol.amigo_id', $amigo_id);

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener árboles disponibles
     */
    public function obtenerArbolesDisponibles()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('arboles');
        $builder->select('arboles.*, especies.nombre_comercial AS especie');
        $builder->join('especies', 'arboles.especie_id = especies.id');
        $builder->where('arboles.estado', 'Disponible');

        return $builder->get()->getResultArray();
    }
}
