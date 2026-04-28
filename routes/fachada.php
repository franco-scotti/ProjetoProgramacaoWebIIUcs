<?php

$basePath = dirname(__DIR__);

include_once($basePath . '/app/model/Usuario.php');
include_once($basePath . '/app/model/Fornecedor.php');
include_once($basePath . '/app/model/Cliente.php');
include_once($basePath . '/app/model/Endereco.php');
include_once($basePath . '/app/model/Produto.php');
include_once($basePath . '/app/model/Estoque.php');
include_once($basePath . '/app/model/Pedido.php');
include_once($basePath . '/app/model/ItemPedido.php');

include_once($basePath . '/app/dao/UsuarioDao.php');
include_once($basePath . '/app/dao/FornecedorDao.php');
include_once($basePath . '/app/dao/ClienteDao.php');
include_once($basePath . '/app/dao/EnderecoDao.php');
include_once($basePath . '/app/dao/ProdutoDao.php');
include_once($basePath . '/app/dao/EstoqueDao.php');
include_once($basePath . '/app/dao/PedidoDao.php');
include_once($basePath . '/app/dao/ItemPedidoDao.php');

include_once($basePath . '/app/dao/DaoFactory.php');
include_once($basePath . '/app/dao/PostgresDao.php');
include_once($basePath . '/app/dao/PostgresUsuarioDao.php');
include_once($basePath . '/app/dao/PostgresFornecedorDao.php');
include_once($basePath . '/app/dao/PostgresClienteDao.php');
include_once($basePath . '/app/dao/PostgresEnderecoDao.php');
include_once($basePath . '/app/dao/PostgresProdutoDao.php');
include_once($basePath . '/app/dao/PostgresEstoqueDao.php');
include_once($basePath . '/app/dao/PostgresPedidoDao.php');
include_once($basePath . '/app/dao/PostgresItemPedidoDao.php');
include_once($basePath . '/app/dao/PostgresDaoFactory.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$factory = new PostgresDaofactory();

?>