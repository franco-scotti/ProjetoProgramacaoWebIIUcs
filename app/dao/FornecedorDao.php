<?php
interface FornecedorDao {

    public function insere($fornecedor);
    public function remove($fornecedor);
    public function removePorId($id);
    public function altera(&$fornecedor);
    public function buscaPorId($id);
    public function buscaPorUsuarioId($usuarioId);
    public function buscaTodos($limit = null, $offset = null);
    public function contaTodos();
    public function buscaPorCodigoNome($termo);
}
?>
