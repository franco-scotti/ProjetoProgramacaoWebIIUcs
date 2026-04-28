<?php
class Cliente {
    
    private $id;
    private $nome;
    private $telefone;
    private $email;
    private $cartaoCredito;
    private $endereco;
    private $pedidos;

    public function __construct( $id, $nome, $telefone, $email, $cartaoCredito)
    {
        $this->id=$id;
        $this->nome=$nome;
        $this->telefone=$telefone;
        $this->email=$email;
        $this->cartaoCredito=$cartaoCredito;
        $this->endereco=null;
        $this->pedidos=array();
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getNome() { return $this->nome; }
    public function setNome($nome) {$this->nome = $nome;}

    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) {$this->telefone = $telefone;}

    public function getEmail() { return $this->email; }
    public function setEmail($email) {$this->email = $email;}

    public function getCartaoCredito() { return $this->cartaoCredito; }
    public function setCartaoCredito($cartaoCredito) {$this->cartaoCredito = $cartaoCredito;}

    public function getEndereco() { return $this->endereco; }
    public function setEndereco($endereco) {$this->endereco = $endereco;}

    public function getPedidos() { return $this->pedidos; }
    public function setPedidos($pedidos) {$this->pedidos = $pedidos;}
    public function addPedido($pedido) {$this->pedidos[] = $pedido;}
}
?>
