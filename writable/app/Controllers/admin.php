<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ArbolModel;
use App\Models\EspecieModel;

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
        $especieModel = new EspecieModel();

        $amigos = $userModel->obtenerAmigos();
        $tab = $this->request->getGet('tab') ?? 'especies';
        $amigo_id = $this->request->getGet('amigo_id');

        $arboles = [];
        $especies = $especieModel->findAll();
        $amigoSeleccionado = null;

        if ($tab === 'amigos' && $amigo_id) {
            $amigoSeleccionado = $userModel->find($amigo_id);
            if ($amigoSeleccionado) {
                $arboles = $arbolModel->where('ubicacion_geografica', $amigoSeleccionado['nombre_usuario'])->findAll();
            }
        }

        $data = [
            'totalAmigos' => $userModel->countAmigos(),
            'totalArbolesDisponibles' => $arbolModel->countArbolesDisponibles(),
            'totalArbolesVendidos' => $arbolModel->countArbolesVendidos(),
            'especies' => $especies,
            'arboles' => $arboles,
            'amigos' => $amigos,
            'amigoSeleccionado' => $amigoSeleccionado,
            'tab' => $tab,
            'mensaje' => session()->getFlashdata('mensaje'),
            'error' => session()->getFlashdata('error'),
        ];

        return view('admin/dashboard', $data);
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
        $id = $this->request->getPost('especie_id');
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
