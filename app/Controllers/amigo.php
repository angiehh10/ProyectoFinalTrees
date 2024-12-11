<?php

namespace App\Controllers;

use App\Models\AmigoArbolModel;
use App\Models\ArbolModel;

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
            'arbolesDisponibles' => $arbolesDisponibles,
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error')
        ];

        return view('amigo/dashboard', $data);
    }

    public function getCompra($id = null)
    {
        $session = session();

        // Verificar rol del usuario
        if ($session->get('rol') !== 'Amigo') {
            return redirect()->to('/')->with('error', 'Acceso no autorizado.');
        }

        $amigoArbolModel = new \App\Models\AmigoArbolModel();

        // Obtener datos del árbol
        $arbol = $amigoArbolModel->obtenerArbolPorId($id);
        if (!$arbol || $arbol['estado'] !== 'Disponible') {
            return redirect()->to('/amigo')->with('error', 'El árbol no está disponible.');
        }

        return view('amigo/comprar', ['arbol' => $arbol]);
    }

    public function postCompra()
    {
        $session = session();

        $amigoArbolModel = new \App\Models\AmigoArbolModel();

        $arbolId = $this->request->getPost('arbol_id');
        $usuarioId = $session->get('usuario_id');

        // Verificar disponibilidad del árbol
        if (!$amigoArbolModel->verificarDisponibilidad($arbolId)) {
            return redirect()->to('/amigo')->with('error', 'El árbol no está disponible.');
        }

        // Registrar la compra
        $compraExitosa = $amigoArbolModel->registrarCompra($usuarioId, $arbolId);
        if ($compraExitosa) {
            // Actualizar el estado del árbol
            $amigoArbolModel->actualizarEstadoArbol($arbolId, 'Vendido');
            return redirect()->to('/amigo')->with('success', '¡Compra realizada con éxito!');
        } else {
            return redirect()->to('/amigo')->with('error', 'Error al realizar la compra.');
        }
    }



    public function actualizaciones($arbolId)
    {
        $session = session();

        // Verificar si el usuario tiene el rol 'Amigo'
        if ($session->get('rol') !== 'Amigo') {
            return redirect()->to('/')->with('error', 'Acceso no autorizado.');
        }

        $usuarioId = $session->get('usuario_id');

        // Cargar el modelo
        $amigoArbolModel = new AmigoArbolModel();
        $db = \Config\Database::connect();

        // Verificar que el árbol pertenece al usuario
        $builder = $db->table('amigo_arbol');
        $builder->where('amigo_id', $usuarioId);
        $builder->where('arbol_id', $arbolId);
        $result = $builder->get()->getRowArray();

        if (!$result) {
            return redirect()->to('/amigo')->with('error', 'No tienes acceso a este árbol.');
        }

        // Obtener las actualizaciones del árbol
        $builder = $db->table('actualizaciones');
        $builder->where('arbol_id', $arbolId);
        $actualizaciones = $builder->get()->getResultArray();

        return view('amigo/actualizaciones', ['actualizaciones' => $actualizaciones]);
    }
}