<?php
interface EstoqueDao {

    public function insere($estoque);
    public function remove($estoque);
    public function removePorId($id);
    public function altera(&$estoque);
    public function buscaPorId($id);
    public function buscaTodos();
}
?>
