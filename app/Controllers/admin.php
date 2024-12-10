<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ArbolModel;
use App\Models\EspecieModel;
use App\Models\AmigoArbolModel;

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

        $tab = $this->request->getGet('tab') ?? 'especies';

        $especies = $especieModel->findAll();
        $usuarios = $userModel->findAll();
        $amigos = $userModel->obtenerAmigos();

        // Inicializa datos específicos
        $totalArbolesDisponibles = $arbolModel->where('estado', 'Disponible')->countAllResults();
        $totalArbolesVendidos = $arbolModel->where('estado', 'Vendido')->countAllResults();

        $data = [
            'totalAmigos' => $userModel->countAmigos(),
            'totalArbolesDisponibles' => $totalArbolesDisponibles,
            'totalArbolesVendidos' => $totalArbolesVendidos,
            'especies' => $especies,
            'arboles' => [], // Inicializa como vacío
            'amigos' => $amigos,
            'usuarios' => $usuarios,
            'tab' => $tab,
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


    public function crearUsuario()
    {
        $this->checkAdmin();

        $userModel = new UserModel();

        $data = [
            'email' => $this->request->getPost('email'),
            'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT),
            'rol' => $this->request->getPost('rol'),
            'estado' => 'Activo',
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('/admin?tab=usuarios')->with('success', 'Usuario creado exitosamente.');
        } else {
            return redirect()->to('/admin?tab=usuarios')->with('error', 'Error al crear el usuario.');
        }
    }

    public function updateTree()
    {
        $arbolModel = new ArbolModel();
        $ids = $this->request->getPost('update'); // Obtener IDs de los árboles que se actualizarán
    
        foreach ($ids as $id => $value) {
            $data = [
                'nombre_comercial' => $this->request->getPost("nombre_comercial[$id]"),
                'tamano' => $this->request->getPost("tamano[$id]"),
                'ubicacion_geografica' => $this->request->getPost("ubicacion_geografica[$id]"),
                'estado' => $this->request->getPost("estado[$id]"),
            ];
    
            // Verificar si se envió un archivo para la imagen
            $file = $this->request->getFile("imagen[$id]");
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Guardar el archivo en la carpeta 'public/uploads'
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads', $fileName); // `FCPATH` apunta al directorio `public/`
                $data['foto'] = $fileName; // Actualizar el campo de la imagen
            }
    
            // Si no se envió una nueva imagen, mantener la anterior
            $existingData = $arbolModel->find($id);
            if (!isset($data['foto']) && $existingData && isset($existingData['foto'])) {
                $data['foto'] = $existingData['foto'];
            }
    
            // Actualizar los datos del árbol en la base de datos
            $arbolModel->update($id, $data);
        }
    
            // Guardar el mensaje en Flashdata
        session()->setFlashdata('success', 'Árboles actualizados correctamente.');

        // Redirigir al usuario
        return redirect()->back();
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
}
