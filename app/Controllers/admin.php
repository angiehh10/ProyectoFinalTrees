<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ArbolModel;
use App\Models\EspecieModel;
use App\Models\AmigoArbolModel;
use App\Models\ActualizacionModel;

class Admin extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    private function checkAdmin()
    {
        $session = session();
        if ($session->get('rol') !== 'Administrador') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Acceso denegado. Solo administradores pueden acceder.');
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $userModel = new UserModel();
        $arbolModel = new ArbolModel();
        $actualizacionModel = new ActualizacionModel();
        $especieModel = new EspecieModel();

        // Obtener el tab seleccionado
        $tab = $this->request->getGet('tab') ?? 'especies';
        $arbol_id = $this->request->getGet('arbol_id');

        // Inicializar datos básicos
        $arboles = $arbolModel->findAll();
        $especies = $especieModel->findAll();
        $usuarios = $userModel->findAll();
        $amigos = $userModel->obtenerAmigos();

        $arbol = null;
        $historial = [];

        // Obtener historial si el tab es 'historial' y se seleccionó un árbol
        if ($tab === 'historial' && $arbol_id) {
            $arbol = $arbolModel->find($arbol_id);
            if ($arbol) {
                $historial = $actualizacionModel->obtenerActualizacionesPorArbol($arbol_id);
            } else {
                session()->setFlashdata('error', 'Árbol no encontrado.');
            }
        }

        // Contar datos generales
        $totalArbolesDisponibles = $arbolModel->where('estado', 'Disponible')->countAllResults();
        $totalArbolesVendidos = $arbolModel->where('estado', 'Vendido')->countAllResults();
        $totalAmigos = $userModel->countAmigos();

        // Preparar datos para la vista
        $data = [
            'tab' => $tab,
            'arbol_id' => $arbol_id,
            'arboles' => $arboles,
            'especies' => $especies,
            'usuarios' => $usuarios,
            'amigos' => $amigos,
            'arbol' => $arbol,
            'historial' => $historial,
            'totalAmigos' => $totalAmigos,
            'totalArbolesDisponibles' => $totalArbolesDisponibles,
            'totalArbolesVendidos' => $totalArbolesVendidos,
            'mensaje' => session()->getFlashdata('mensaje'),
            'error' => session()->getFlashdata('error'),
        ];

        return view('admin/dashboard', $data);
    }


    public function createTree()
    {
        $this->checkAdmin(); // Verifica si el usuario es administrador.

        $arbolModel = new ArbolModel();

        // Validación de datos
        $validation = \Config\Services::validation();
        $validation->setRules([
            'especie_id' => 'required|integer',
            'ubicacion_geografica' => 'required|max_length[255]',
            'estado' => 'required|in_list[Disponible,Vendido]',
            'precio' => 'required|decimal',
            'tamano' => 'required|max_length[50]',
            'foto_upload' => 'permit_empty|uploaded[foto_upload]|max_size[foto_upload,1024]|is_image[foto_upload]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }

        // Procesamiento de datos
        $data = [
            'especie_id' => $this->request->getPost('especie_id'),
            'ubicacion_geografica' => $this->request->getPost('ubicacion_geografica'),
            'estado' => $this->request->getPost('estado'),
            'precio' => $this->request->getPost('precio'),
            'tamano' => $this->request->getPost('tamano'),
        ];

        // Procesar imagen
        $file = $this->request->getFile('foto_upload');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $fileName);
            $data['foto'] = $fileName;
        }

        // Insertar en la base de datos
        if ($arbolModel->insert($data)) {
            session()->setFlashdata('mensaje', 'Árbol creado exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al crear el árbol.');
        }

        return redirect()->to('/admin?tab=arboles');
    }

    public function updateTree()
    {
        $arbolModel = new ArbolModel();
        $actualizacionModel = new ActualizacionModel();
        $ids = $this->request->getPost('update'); // Obtener IDs de los árboles que se actualizarán
    
        if (!$ids || empty($ids)) {
            session()->setFlashdata('error', 'No se seleccionaron árboles para actualizar.');
            return redirect()->back();
        }
    
        foreach ($ids as $id => $value) {
            // Datos para actualizar en la tabla `arboles`
            $dataArbol = [
                'nombre_comercial' => $this->request->getPost("nombre_comercial[$id]"),
                'tamano' => $this->request->getPost("tamano[$id]"),
                'ubicacion_geografica' => $this->request->getPost("ubicacion_geografica[$id]"),
                'estado' => $this->request->getPost("estado[$id]"),
            ];
    
            // Manejar la imagen, si se envió una
            $file = $this->request->getFile("imagen[$id]");
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads', $fileName);
                $dataArbol['foto'] = $fileName; // Actualizar el campo de la imagen en `arboles`
            } else {
                // Si no se subió una nueva imagen, mantener la anterior
                $existingData = $arbolModel->find($id);
                if ($existingData && isset($existingData['foto'])) {
                    $dataArbol['foto'] = $existingData['foto'];
                }
            }
    
            // Actualizar los datos del árbol en la tabla `arboles`
            $arbolModel->update($id, $dataArbol);
    
            // Registrar una nueva actualización en la tabla `actualizaciones`
            $dataActualizacion = [
                'arbol_id' => $id,
                'tamano' => $dataArbol['tamano'],
                'estado' => $dataArbol['estado'],
                'fecha_actualizacion' => date('Y-m-d H:i:s'),
            ];
    
            if (isset($dataArbol['foto'])) {
                $dataActualizacion['foto'] = $dataArbol['foto'];
            }
    
            $actualizacionModel->insert($dataActualizacion);
        }
    
        // Guardar el mensaje en Flashdata
        session()->setFlashdata('success', 'Árboles actualizados y actualizaciones registradas correctamente.');
    
        // Redirigir al usuario
        return redirect()->back();
    }
    
    public function historial($arbol_id = null)
    {
        $this->checkAdmin();

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

        return view('admin/historial', $data);
    }


    public function crearUsuario()
    {
        $this->checkAdmin();

        $userModel = new UserModel();

        $data = [
            'email' => $this->request->getPost('email'),
            // Encriptar la contraseña con hash SHA-256
            'contrasena' => hash('sha256', $this->request->getPost('contrasena')),
            'rol' => $this->request->getPost('rol'),
            'estado' => 'Activo',
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('/admin?tab=usuarios')->with('success', 'Usuario creado exitosamente.');
        } else {
            return redirect()->to('/admin?tab=usuarios')->with('error', 'Error al crear el usuario.');
        }
    }


    public function viewFriendTrees($amigo_id)
    {
        $this->checkAdmin();

        $userModel = new UserModel();
        $arbolModel = new AmigoArbolModel();

        // Validar si el amigo existe
        $amigo = $userModel->find($amigo_id);
        if (!$amigo || $amigo['rol'] !== 'Amigo') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Amigo no encontrado o no válido.');
        }

        // Obtener los árboles del amigo
        $arboles = $arbolModel->obtenerArbolesPorAmigo($amigo_id);

        // Enviar los datos a la vista
        return view('admin/tabs/view_friend_trees', [
            'amigo' => $amigo,
            'arboles' => $arboles,
        ]);
    }

    public function createSpecies()
    {
        $this->checkAdmin();

        $especieModel = new EspecieModel();
        $validation = \Config\Services::validation();

        $validation->setRules([
            'nombre_comercial' => 'required|max_length[255]',
            'nombre_cientifico' => 'required|max_length[255]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/admin?tab=especies')->with('error', $validation->getErrors());
        }

        $data = [
            'nombre_comercial' => $this->request->getPost('nombre_comercial'),
            'nombre_cientifico' => $this->request->getPost('nombre_cientifico'),
        ];

        if ($especieModel->save($data)) {
            session()->setFlashdata('mensaje', 'Especie creada exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al crear la especie.');
        }

        return redirect()->to('/admin?tab=especies');
    }

    public function updateSpecies()
    {
        $this->checkAdmin();

        $especieModel = new EspecieModel();
        $id = $this->request->getPost('especie_id'); // Recupera el ID de la especie desde el formulario
        $data = [
            'nombre_comercial' => $this->request->getPost('nombre_comercial'),
            'nombre_cientifico' => $this->request->getPost('nombre_cientifico'),
        ];

        if ($especieModel->update($id, $data)) {
            session()->setFlashdata('mensaje', 'Especie actualizada exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al actualizar la especie.');
        }

        return redirect()->to('/admin?tab=especies');
    }

    public function deleteSpecies($id)
    {
        $this->checkAdmin();

        $especieModel = new EspecieModel();

        if ($especieModel->delete($id)) {
            session()->setFlashdata('mensaje', 'Especie eliminada exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al eliminar la especie.');
        }

        return redirect()->to('/admin?tab=especies');
    }
}
