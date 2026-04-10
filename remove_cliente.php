<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getClienteDao();
$dao->removePorId($id);

header("Location: clientes.php");
exit;
?>
