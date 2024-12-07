<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
        $loginError = null;

        // Si el método es POST, procesar el formulario de inicio de sesión
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $contrasena = $this->request->getPost('contrasena');

            // Llamar a la función para autenticar al usuario (ejemplo de modelo)
            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();

            if ($user && password_verify($contrasena, $user['password'])) {
                // Redirigir al usuario según su rol
                if ($user['role'] === 'admin') {
                    return redirect()->to('/admin');
                } else {
                    return redirect()->to('/user');
                }
            } else {
                // Si hay error en el login
                $loginError = 'Correo o contraseña incorrectos.';
            }
        }

        // Pasar la variable de error a la vista
        return view('home/index', ['loginError' => $loginError]);
    }
}
