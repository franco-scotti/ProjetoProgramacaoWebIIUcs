<?php
interface ProdutoDao {

    public function insere($produto);
    public function remove($produto);
    public function removePorId($id);
    public function altera(&$produto);
    public function buscaPorId($id);
    public function buscaTodos($limit = null, $offset = null);
    public function buscaPorCodigoNome($termo);
    public function buscaPorFornecedorId($fornecedorId, $limit = null, $offset = null);
    public function buscaPorCodigoNomeFornecedor($termo, $fornecedorId);
    public function contaTodos();
    public function contaPorFornecedor($fornecedorId);
}
?>
