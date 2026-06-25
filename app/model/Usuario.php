<?php
class Usuario {
    
    private $id;
    private $login;
    private $senha;
    private $nome;
    private $admin;

    public function __construct($id, $login, $senha, $nome, $admin = false)
    {
        $this->id = $id;
        $this->login = $login;
        $this->senha = $senha;
        $this->nome = $nome;
        $this->admin = (bool)$admin;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getLogin() { return $this->login; }
    public function setLogin($login) { $this->login = $login; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getSenha() { return $this->senha; }
    public function setSenha($senha) { $this->senha = $senha; }

    public function isAdmin() { return $this->admin; }
    public function setAdmin($admin) { $this->admin = (bool)$admin; }
}
?>