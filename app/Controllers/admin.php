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

        $tab = $this->request->getGet('tab') ?? 'actualizacion';
        $arbol_id = $this->request->getGet('arbol_id'); // ID del árbol seleccionado

        $arboles = $arbolModel->findAll(); // Obtener todos los árboles
        $especies = $especieModel->findAll(); // Obtener todas las especies
        $arbol = null; // Inicializar como null para evitar errores si no hay árbol seleccionado
        $historial = [];

        if ($tab === 'historial' && $arbol_id) {
            $arbol = $arbolModel->find($arbol_id); // Obtener los detalles del árbol seleccionado
            if ($arbol) {
                $historial = $actualizacionModel->obtenerActualizacionesPorArbol($arbol_id); // Historial del árbol
            }
        }

        $data = [
            'totalAmigos' => $userModel->countAmigos(),
            'totalArbolesDisponibles' => $arbolModel->countArbolesDisponibles(),
            'totalArbolesVendidos' => $arbolModel->countArbolesVendidos(),
            'tab' => $tab,
            'arboles' => $arboles,
            'especies' => $especies,
            'arbol' => $arbol, // Enviar el árbol seleccionado o null
            'historial' => $historial, // Historial de actualizaciones del árbol seleccionado
        ];

        return view('admin/dashboard', $data);
    }


    public function verAmigos()
    {
        $this->checkAdmin();

        $amigoModel = new UserModel(); // Modelo de amigos
        $amigoArbolModel = new AmigoArbolModel(); // Modelo de relación amigo-árbol
        $amigoSeleccionado = $this->request->getPost('amigo_id'); // ID del amigo seleccionado

        $amigos = $amigoModel->where('rol', 'Amigo')->findAll(); // Lista de amigos
        $arboles = [];

        // Si se selecciona un amigo, obtener sus árboles
        if (!empty($amigoSeleccionado)) {
            $arboles = $amigoArbolModel->obtenerArbolesPorAmigo($amigoSeleccionado);
        }

        // Enviar datos a la vista
        return view('admin/amigos', [
            'amigos' => $amigos,
            'arboles' => $arboles,
            'amigoSeleccionado' => $amigoSeleccionado
        ]);
    }

    public function registrarActualizacion()
    {
        $this->checkAdmin(); // Verifica si el usuario es administrador

        $actualizacionModel = new ActualizacionModel();

        $data = [
            'arbol_id' => $this->request->getPost('arbol_id'),
            'tamano' => $this->request->getPost('tamano'),
            'estado' => $this->request->getPost('estado'),
            'fecha_actualizacion' => date('Y-m-d H:i:s'),
        ];

        // Manejar la foto
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid()) {
            $filePath = WRITEPATH . 'uploads';
            $file->move($filePath);
            $data['foto'] = '/uploads/' . $file->getName();
        }

        if ($actualizacionModel->registrarActualizacion($data)) {
            return redirect()->to('/admin?tab=actualizacion')->with('mensaje', 'Actualización registrada correctamente.');
        } else {
            return redirect()->back()->with('error', 'No se pudo registrar la actualización.');
        }
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

    public function getFriendTrees($amigo_id)
    {
        $this->checkAdmin();
    
        $userModel = new UserModel();
        $arbolModel = new ArbolModel();
    
        // Validar si el amigo existe
        $amigo = $userModel->find($amigo_id);
        if (!$amigo || $amigo['rol'] !== 'Amigo') {
            return $this->response->setJSON(['error' => 'Amigo no válido o no encontrado.']);
        }
    
        // Conectar a la base de datos
        $db = \Config\Database::connect();
    
        // Obtener los IDs de los árboles asociados al amigo
        $query = $db->table('amigo_arbol')
                    ->select('arbol_id')
                    ->where('amigo_id', $amigo_id)
                    ->get();
    
        $arbolIds = array_column($query->getResultArray(), 'arbol_id');
    
        // Si no hay árboles asociados, devolver vacío
        if (empty($arbolIds)) {
            return $this->response->setJSON(['amigo' => $amigo, 'arboles' => []]);
        }
    
        // Obtener los detalles de los árboles de la tabla `arboles`
        $arboles = $arbolModel->whereIn('id', $arbolIds)->findAll();
    
        return $this->response->setJSON(['amigo' => $amigo, 'arboles' => $arboles]);
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

    public function createTree()
    {
        $this->checkAdmin();

        $arbolModel = new ArbolModel();
        $data = [
            'especie_id' => $this->request->getPost('especie_id'),
            'ubicacion_geografica' => $this->request->getPost('ubicacion_geografica'),
            'estado' => $this->request->getPost('estado'),
            'precio' => $this->request->getPost('precio'),
            'tamano' => $this->request->getPost('tamano'),
        ];

        $file = $this->request->getFile('foto_upload');
        if ($file && $file->isValid()) {
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                $file->move(WRITEPATH . 'uploads');
                $data['foto'] = '/uploads/' . $file->getName();
            } else {
                session()->setFlashdata('error', 'Formato de archivo no permitido. Solo se permiten imágenes.');
                return redirect()->to('/admin?tab=arboles');
            }
        }

        if ($arbolModel->save($data)) {
            session()->setFlashdata('mensaje', 'Árbol creado exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al crear el árbol.');
        }

        return redirect()->to('/admin?tab=arboles');
    }

    public function updateTree()
    {
        $this->checkAdmin();

        $arbolModel = new ArbolModel();
        $id = $this->request->getPost('arbol_id');
        $data = [
            'especie_id' => $this->request->getPost('especie_id'),
            'ubicacion_geografica' => $this->request->getPost('ubicacion_geografica'),
            'estado' => $this->request->getPost('estado'),
            'tamano' => $this->request->getPost('tamano'),
        ];

        $file = $this->request->getFile('foto_upload');
        if ($file && $file->isValid()) {
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                $file->move(WRITEPATH . 'uploads');
                $data['foto'] = '/uploads/' . $file->getName();
            } else {
                session()->setFlashdata('error', 'Formato de archivo no permitido. Solo se permiten imágenes.');
                return redirect()->to('/admin?tab=arboles');
            }
        }

        if ($arbolModel->update($id, $data)) {
            session()->setFlashdata('mensaje', 'Árbol actualizado exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al actualizar el árbol.');
        }

        return redirect()->to('/admin?tab=arboles');
    }
}
