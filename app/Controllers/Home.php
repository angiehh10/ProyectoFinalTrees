<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
        return view('home/index');
    }

    public function processLogin()
    {
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');

        $userModel = new UserModel();
        $user = $userModel->loginUser($email, $contrasena);

        if ($user && !isset($user['error'])) {
            // Guardar datos en la sesión
            session()->set([
                'usuario_id' => $user['id'],
                'rol' => $user['rol'],
                'estado' => $user['estado'],
                'isLoggedIn' => true,
            ]);

            // Redirigir según el rol
            switch ($user['rol']) {
                case 'Administrador':
                    return redirect()->to('/admin');
                case 'Amigo':
                    return redirect()->to('/amigo');
                case 'Operador':
                    return redirect()->to('/operador');
                default:
                    session()->destroy(); // Si el rol es desconocido, destruir la sesión
                    return redirect()->to('/login')->with('error', 'Rol no autorizado.');
            }
        }

        return redirect()->to('/login')->with('error', 'Usuario o contraseña incorrectos.');
    }
    
    public function logout()
    {
    session()->destroy(); // Destruye la sesión
    return redirect()->to('/'); // Redirige a la página principal
    }

    public function register()
    {
        return view('home/register'); // Mostrar formulario de registro
    }

    public function store()
    {
        $userModel = new UserModel();

        $data = [
            'nombre_usuario' => $this->request->getPost('nombre_usuario'),
            'email' => $this->request->getPost('email'),
            'contrasena' => hash('sha256', $this->request->getPost('contrasena')),
            'rol' => 'Amigo',
            'estado' => 'Activo',
            'nombre' => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion'),
            'pais' => $this->request->getPost('pais'),
        ];

        if ($userModel->save($data)) {
            return redirect()->to('/register')->with('success', 'Registro exitoso. ¡Ahora puedes iniciar sesión!');
        }

        return redirect()->to('/register')->with('error', 'Error al registrar el usuario. Intenta nuevamente.');
    }
}


