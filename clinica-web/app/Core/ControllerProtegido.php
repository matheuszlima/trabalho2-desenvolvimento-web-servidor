<?php

// Controller que so pode ser acessado por usuarios autenticados (heranca de Controller)
abstract class ControllerProtegido extends Controller
{
    use ExigeLogin;

    public function __construct()
    {
        $this->exigirLogin();
    }
}
