<?php

// Login e logout usando Sessions (Aula 05)
class AuthController extends Controller
{
    public function login()
    {
        // Quem ja esta logado vai direto para a pagina inicial
        if (isset($_SESSION['logado']) && $_SESSION['logado'] === "true") {
            $this->redirecionar('/');
        }

        $erro = '';
        $email = '';

        if ($this->requisicaoPost()) {
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if ($email === '' || $senha === '') {
                $erro = 'Informe o e-mail e a senha.';
            } else {
                $repositorio = new UsuarioRepositorio();
                $usuario = $repositorio->buscarPorEmail($email);

                if ($usuario !== null && password_verify($senha, $usuario->senha_hash)) {
                    session_regenerate_id(true); // evita fixacao de sessao
                    $_SESSION['logado'] = "true";
                    $_SESSION['usuario'] = $usuario->nome;
                    $_SESSION['usuario_id'] = $usuario->id;
                    $this->redirecionar('/');
                } else {
                    $erro = 'E-mail ou senha inválidos.';
                }
            }
        }

        $this->view('login', ['erro' => $erro, 'email' => $email]);
    }

    public function logout()
    {
        session_destroy();
        $this->redirecionar('/login');
    }
}
