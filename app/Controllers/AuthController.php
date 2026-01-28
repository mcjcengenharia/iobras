<?php

class AuthController
{
    public function loginForm(): void
    {
        Auth::start();
        View::render('auth/login', [
            'csrf' => Csrf::token(),
            'error' => null
        ]);
    }

    public function login(): void
    {
        Auth::start();
        Csrf::check();

        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';

        $u = UserModel::findByEmail($email);

        if (!$u || !password_verify($pass, $u['password_hash'])) {
            View::render('auth/login', [
                'csrf' => Csrf::token(),
                'error' => 'Email ou senha inválidos'
            ]);
            return;
        }

        Auth::login($u);
        header('Location: /iobras/public/');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: /iobras/public/login');
        exit;
    }
}
