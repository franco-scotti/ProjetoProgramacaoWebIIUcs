<?php
class Produto {
    
    private $id;
    private $nome;
    private $descricao;
    private $foto;
    private $fornecedor;
    private $estoque;
    private $itensPedido;

    public function __construct( $id, $nome, $descricao, $foto)
    {
        $this->id=$id;
        $this->nome=$nome;
        $this->descricao=$descricao;
        $this->foto=$foto;
        $this->fornecedor=null;
        $this->estoque=null;
        $this->itensPedido=array();
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getNome() { return $this->nome; }
    public function setNome($nome) {$this->nome = $nome;}

    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) {$this->descricao = $descricao;}

    public function getFoto() { return $this->foto; }
    public function setFoto($foto) {$this->foto = $foto;}

    public function getFornecedor() { return $this->fornecedor; }
    public function setFornecedor($fornecedor) {$this->fornecedor = $fornecedor;}

    public function getEstoque() { return $this->estoque; }
    public function setEstoque($estoque) {$this->estoque = $estoque;}

    public function getItensPedido() { return $this->itensPedido; }
    public function setItensPedido($itensPedido) {$this->itensPedido = $itensPedido;}
    public function addItemPedido($itemPedido) {$this->itensPedido[] = $itemPedido;}
}
?>
