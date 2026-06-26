<?php
interface EstoqueDao {

    public function insere($estoque);
    public function remove($estoque);
    public function removePorId($id);
    public function altera(&$estoque);
    public function buscaPorId($id);
    public function buscaTodos($limit = null, $offset = null);
    public function buscaPorCodigoNome($termo);
    public function buscaPorFornecedorId($fornecedorId);
    public function contaTodos();
}
?>
