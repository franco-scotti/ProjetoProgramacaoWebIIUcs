<?php
class Pedido {
    
    private $id;
    private $numero;
    private $dataPedido;
    private $dataEntrega;
    private $situacao;
    private $cliente;
    private $itensPedido;

    public function __construct( $id, $numero, $dataPedido, $dataEntrega, $situacao)
    {
        $this->id=$id;
        $this->numero=$numero;
        $this->dataPedido=$dataPedido;
        $this->dataEntrega=$dataEntrega;
        $this->situacao=$situacao;
        $this->cliente=null;
        $this->itensPedido=array();
    }

    public function getId() { return $this->id; }
    public function setId($id) {$this->id = $id;}

    public function getNumero() { return $this->numero; }
    public function setNumero($numero) {$this->numero = $numero;}

    public function getDataPedido() { return $this->dataPedido; }
    public function setDataPedido($dataPedido) {$this->dataPedido = $dataPedido;}

    public function getDataEntrega() { return $this->dataEntrega; }
    public function setDataEntrega($dataEntrega) {$this->dataEntrega = $dataEntrega;}

    public function getSituacao() { return $this->situacao; }
    public function setSituacao($situacao) {$this->situacao = $situacao;}

    public function getCliente() { return $this->cliente; }
    public function setCliente($cliente) {$this->cliente = $cliente;}

    public function getItensPedido() { return $this->itensPedido; }
    public function setItensPedido($itensPedido) {$this->itensPedido = $itensPedido;}
    public function addItemPedido($itemPedido) {$this->itensPedido[] = $itemPedido;}
}
?>
