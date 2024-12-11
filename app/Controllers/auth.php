<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        $loginError = null;

        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $contrasena = $this->request->getPost('contrasena');

            $userModel = new UserModel();
            $user = $userModel->loginUser($email, $contrasena);

            if ($user && !isset($user['error'])) {
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
                        session()->destroy(); // Si el rol es desconocido, destruye la sesión
                        return redirect()->to('/login')->with('error', 'Rol no autorizado.');
                }
            } else {
                $loginError = $user['error'] ?? 'Correo o contraseña incorrectos.';
            }
        }

        return view('auth/login', ['loginError' => $loginError]);
    }
}

