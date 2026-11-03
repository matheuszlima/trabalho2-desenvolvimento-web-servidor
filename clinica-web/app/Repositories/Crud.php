<?php

// Interface (Aula 07): contrato que todo repositorio de CRUD precisa cumprir
interface Crud
{
    public function listar();
    public function buscar($id);
    public function salvar($entidade);
    public function excluir($id);
}
