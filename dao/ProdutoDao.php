<?php
interface ProdutoDao {

    public function insere($produto);
    public function remove($produto);
    public function removePorId($id);
    public function altera(&$produto);
    public function buscaPorId($id);
    public function buscaTodos($limit = null, $offset = null);
    public function contaTodos();
}
?>
