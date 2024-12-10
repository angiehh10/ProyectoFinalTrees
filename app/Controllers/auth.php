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
                return redirect()->to($user['rol'] === 'Administrador' ? '/admin' : '/amigo');
            } else {
                $loginError = $user['error'] ?? 'Error desconocido al iniciar sesión.';
            }
        }

        return view('auth/login', ['loginError' => $loginError]);
    }
}
