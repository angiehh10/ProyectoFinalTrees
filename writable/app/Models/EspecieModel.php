<?php

namespace App\Models;

use CodeIgniter\Model;

class EspecieModel extends Model
{
    // Configuración básica del modelo
    protected $table = 'especies'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria
    protected $allowedFields = ['nombre_comercial', 'nombre_cientifico']; // Campos permitidos para escritura

    public function crearEspecie($nombre_comercial, $nombre_cientifico)
    {
        $data = [
            'nombre_comercial' => $nombre_comercial,
            'nombre_cientifico' => $nombre_cientifico,
        ];

        return $this->insert($data); // Inserta los datos y devuelve true/false
    }

    public function obtenerEspecies()
    {
        return $this->findAll(); // Devuelve todos los registros de la tabla
    }

    public function actualizarEspecie($id, $nombre_comercial, $nombre_cientifico)
    {
        $data = [
            'nombre_comercial' => $nombre_comercial,
            'nombre_cientifico' => $nombre_cientifico,
        ];

        return $this->update($id, $data); // Actualiza el registro por ID y devuelve true/false
    }

    public function eliminarEspecie($id)
    {
        return $this->delete($id); // Elimina el registro por ID y devuelve true/false
    }
}

