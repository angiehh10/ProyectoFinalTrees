<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ArbolModel;
use App\Models\ActualizacionModel;

class Operador extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    private function checkOperator()
    {
        $session = session();
        if ($session->get('rol') !== 'Operador') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Acceso denegado. Solo operadores pueden acceder.');
        }
    }

    

    public function index()
    {
        $this->checkOperator();

        $userModel = new UserModel();
        $arbolModel = new ArbolModel();
        $actualizacionModel = new ActualizacionModel();

        $tab = $this->request->getGet('tab') ?? 'actualizacion';
        $arbol_id = $this->request->getGet('arbol_id'); // ID del árbol seleccionado

        $arboles = $arbolModel->findAll();
        $arbol = null;
        $historial = [];

        if ($tab === 'historial' && $arbol_id) {
            $arbol = $arbolModel->find($arbol_id); // Obtener los detalles del árbol
            if ($arbol) {
                $historial = $actualizacionModel->obtenerActualizacionesPorArbol($arbol_id);
            }
        }

        $data = [ 
            'totalAmigos' => $userModel->countAmigos(),
            'totalArbolesDisponibles' => $arbolModel->countArbolesDisponibles(),
            'tab' => $tab,
            'arboles' => $arboles,
            'arbol' => $arbol, // Enviar el árbol seleccionado
            'historial' => $historial, // Historial de actualizaciones del árbol seleccionado
        ];

        return view('operador/dashboard', $data); // Utiliza la misma variable $data
    }

    public function historial($arbol_id = null)
    {
        $this->checkOperator();

        $actualizacionModel = new ActualizacionModel();
        $arbolModel = new ArbolModel();

        if ($arbol_id) {
            $historial = $actualizacionModel->obtenerActualizacionesPorArbol($arbol_id);
        } else {
            $historial = []; // Si no se pasa un árbol, muestra el historial vacío
        }

        $data = [
            'historial' => $historial,
            'arbol' => $arbolModel->find($arbol_id), // Obtener detalles del árbol
        ];

        return view('operator/historial', $data);
    }

    public function registrarActualizacion()
    {
        $this->checkOperator(); // Verifica si el usuario es operador

        $actualizacionModel = new ActualizacionModel();

        // Validación de datos
        $validation = \Config\Services::validation();
        $validation->setRules([
            'arbol_id' => 'required|integer',
            'tamano' => 'required|max_length[50]',
            'estado' => 'required|in_list[Disponible,Vendido]',
            'foto' => 'permit_empty|uploaded[foto]|max_size[foto,1024]|is_image[foto]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Si la validación falla, regresar con errores
            return redirect()->back()->withInput()->with('error', implode('<br>', $validation->getErrors()));
        }

        // Procesar datos de la actualización
        $data = [
            'arbol_id' => $this->request->getPost('arbol_id'),
            'tamano' => $this->request->getPost('tamano'),
            'estado' => $this->request->getPost('estado'),
            'fecha_actualizacion' => date('Y-m-d H:i:s'),
        ];

        // Manejar la foto
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $fileName); // Mover a la carpeta `public/uploads`
            $data['foto'] = $fileName; // Guardar la ruta relativa
        }

        // Intentar registrar la actualización
        if ($actualizacionModel->insert($data)) {
            // Mensaje de éxito
            return redirect()->back()->with('mensaje', 'Actualización registrada correctamente.');
        } else {
            // Mensaje de error si falla la inserción
            return redirect()->back()->with('error', 'No se pudo registrar la actualización.');
        }
    }

}