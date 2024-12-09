<?php

namespace App\Controllers;

use App\Models\AmigoArbolModel;

class Amigo extends BaseController
{
    public function index()
    {
        $session = session();

        // Verificar si el usuario tiene el rol 'Amigo'
        if ($session->get('rol') !== 'Amigo') {
            return redirect()->to('/')->with('error', 'Acceso no autorizado.');
        }

        $usuario_id = $session->get('usuario_id');

        // Cargar el modelo
        $amigoModel = new AmigoArbolModel();

        // Obtener datos
        $arbolesAmigo = $amigoModel->obtenerArbolesPorAmigo($usuario_id);
        $arbolesDisponibles = $amigoModel->obtenerArbolesDisponibles();

        // Enviar datos a la vista
        $data = [
            'arbolesAmigo' => $arbolesAmigo,
            'arbolesDisponibles' => $arbolesDisponibles
        ];

        return view('amigo/dashboard', $data);
    }
}
