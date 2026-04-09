<?php
class ItemPedido {
    
    private $id;
    private $quantidade;
    private $preco;
    private $pedido;
    private $produto;

    public function __construct( $id, $quantidade, $preco)
    {
        $this->id=$id;
        $this->quantidade=$quantidade;
        $this->preco=$preco;
        $this->pedido=null;
        $this->produto=null;
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getQuantidade() { return $this->quantidade; }
    public function setQuantidade($quantidade) {$this->quantidade = $quantidade;}

    public function getPreco() { return $this->preco; }
    public function setPreco($preco) {$this->preco = $preco;}

    public function getPedido() { return $this->pedido; }
    public function setPedido($pedido) {$this->pedido = $pedido;}

    public function getProduto() { return $this->produto; }
    public function setProduto($produto) {$this->produto = $produto;}
}
?>
