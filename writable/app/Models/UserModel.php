<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Configuración del modelo
    protected $table = 'usuarios'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria
    protected $allowedFields = [
        'nombre_usuario', 'email', 'contrasena', 'rol', 'estado', 
        'nombre', 'apellidos', 'telefono', 'direccion', 'pais'
    ]; // Campos permitidos para escritura

    public function loginUser($email, $contrasena)
    {
        // Buscar usuario por email
        $user = $this->where('email', $email)->first();

        if ($user) {
            // Verificar contraseña (hash SHA-256)
            if (hash('sha256', $contrasena) === $user['contrasena']) {
                // Verificar si el usuario está activo
                if ($user['estado'] === 'Activo') {
                    return $user; // Retornar datos del usuario
                } else {
                    return ['error' => 'Acceso denegado o cuenta inactiva.'];
                }
            } else {
                return ['error' => 'Contraseña incorrecta.'];
            }
        }

        return ['error' => 'Usuario no encontrado.'];
    }

    public function registerUser($data)
    {
        // Verificar si el correo ya está registrado
        if ($this->where('email', $data['email'])->first()) {
            return 'El correo electrónico ya está registrado.';
        }

        // Hashear la contraseña
        $data['contrasena'] = hash('sha256', $data['contrasena']);

        // Insertar el nuevo usuario
        if ($this->insert($data)) {
            return true; // Registro exitoso
        }

        return 'Error al registrar el usuario.';
    }

    public function obtenerAmigos()
    {
        return $this->where('rol', 'Amigo')->findAll();
    }

    public function obtenerNombreAmigo($amigo_id)
    {
        $amigo = $this->find($amigo_id); // Buscar usuario por ID

        return $amigo ? $amigo['nombre'] : null; // Retornar nombre o null
    }

    public function countAmigos()
    {
        return $this->where('rol', 'Amigo')->countAllResults();
    }
}
