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
            'arbolesDisponibles' => $arbolesDisponibles,
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error')
        ];

        return view('amigo/dashboard', $data);
    }

    public function comprar($id = null)
    {
        $session = session();
    
        // Verificar el rol del usuario
        if ($session->get('rol') !== 'Amigo') {
            return redirect()->to('/')->with('error', 'Acceso no autorizado.');
        }
    
        $amigoArbolModel = new \App\Models\AmigoArbolModel();
    
        // Si la solicitud es POST, procesamos la compra
        if ($this->request->getMethod() === 'post') {
            $usuarioId = $session->get('usuario_id');
    
            // Verificar si el árbol está disponible
            if (!$amigoArbolModel->verificarDisponibilidad($id)) {
                return redirect()->to('/amigo')->with('error', 'El árbol no está disponible.');
            }
    
            // Registrar la compra
            $compraExitosa = $amigoArbolModel->registrarCompra($usuarioId, $id);
            if ($compraExitosa) {
                // Actualizar el estado del árbol
                $estadoActualizado = $amigoArbolModel->actualizarEstadoArbol($id, 'Vendido');
                if ($estadoActualizado) {
                    return redirect()->to('/amigo')->with('success', '¡Compra realizada con éxito!');
                } else {
                    return redirect()->to('/amigo')->with('error', 'Error al actualizar el estado del árbol.');
                }
            } else {
                return redirect()->to('/amigo')->with('error', 'Error al realizar la compra.');
            }
        }
    
        // Si la solicitud es GET, mostramos la vista de compra
        $arbol = $amigoArbolModel->obtenerArbolPorId($id);
        if (!$arbol) {
            return redirect()->to('/amigo')->with('error', 'Árbol no encontrado.');
        }
    
        return view('amigo/comprar', ['arbol' => $arbol]);
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


    public function detalles($id)
    {
        $session = session();
    
        // Verificar si el usuario tiene el rol 'Amigo'
        if ($session->get('rol') !== 'Amigo') {
            return redirect()->to('/')->with('error', 'Acceso no autorizado.');
        }
    
        // Cargar el modelo
        $amigoArbolModel = new \App\Models\AmigoArbolModel();
    
        // Obtener detalles del árbol por ID
        $arbol = $amigoArbolModel->obtenerArbolPorId($id);
    
        // Si no se encuentra el árbol, redirige con un mensaje de error
        if (!$arbol) {
            return redirect()->to('/amigo')->with('error', 'Árbol no encontrado.');
        }
    
        // Retorna la vista con los detalles del árbol
        return view('amigo/detalles', ['arbol' => $arbol]);
    }
}
