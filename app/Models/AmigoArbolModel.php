<?php

namespace App\Models;

use CodeIgniter\Model;

class AmigoArbolModel extends Model
{
    protected $table = 'amigo_arbol';
    protected $primaryKey = 'id';
    protected $allowedFields = ['amigo_id', 'arbol_id'];

    public function obtenerArbolPorId($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('arboles');
        $builder->select('arboles.*, especies.nombre_comercial, especies.nombre_cientifico');
        $builder->join('especies', 'arboles.especie_id = especies.id');
        $builder->where('arboles.id', $id);

        return $builder->get()->getRowArray();
    }
  
    public function obtenerArbolesPorAmigo($amigo_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('arboles.*, especies.nombre_comercial AS nombre_comercial');
        $builder->join('arboles', 'amigo_arbol.arbol_id = arboles.id');
        $builder->join('especies', 'arboles.especie_id = especies.id');
        $builder->where('amigo_arbol.amigo_id', $amigo_id);

        return $builder->get()->getResultArray();
    }


    public function registrarCompra($amigoId, $arbolId)
    {
        return $this->insert([
            'amigo_id' => $amigoId,
            'arbol_id' => $arbolId,
        ]);
    }

    public function actualizarEstadoArbol($arbolId, $estado)
    {
        return $this->db->table('arboles')
            ->where('id', $arbolId)
            ->update(['estado' => $estado]);
    }

    
    public function obtenerArbolesDisponibles()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('arboles');
        $builder->select('arboles.*, especies.nombre_comercial AS especie');
        $builder->join('especies', 'arboles.especie_id = especies.id');
        $builder->where('arboles.estado', 'Disponible');

        return $builder->get()->getResultArray();
    }
    
    public function verificarDisponibilidad($arbolId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('arboles');
        $builder->where('id', $arbolId);
        $builder->where('estado', 'Disponible');

        return $builder->countAllResults() > 0; // Retorna true si está disponible
    }

}