<?php
include("./conf/config.php");
protegePagina();
$usuario=$_SESSION['usuarioID'];

$qr=mysqli_query($_SG['link'],"SELECT * FROM usuarios where id='$usuario'");
$row=mysqli_fetch_array($qr);
$n=$row['n_acesso_f'];
$n_acesso=$n+1;
mysqli_query($_SG['link'],"UPDATE usuarios SET n_acesso_f='$n_acesso' WHERE id='$usuario'");

$data = date("YmdHis");
mysqli_query($_SG['link'],"UPDATE usuarios SET ultimavisita='$data' WHERE id='$usuario'");
echo mysqli_error($_SG['link']);

@session_start();
session_destroy(); // Destrói a sess&atilde;o limpando todos os valores salvos
session_unset(); // Limpa as variavéis globais da se&ccedil;&atilde;o

header( 'Location: login.php' ) ;
?>