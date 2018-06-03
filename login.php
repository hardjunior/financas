<?php
//echo md5("123456");exit;
session_start();
session_destroy(); // Destrói a sess&atilde;o limpando todos os valores salvos
include('./conf/config.php');
include './conf/functions.php';
require_once './conf/versao.php';

// Formato 24 horas (de 1 a 24)
$hora = date('G');
if (($hora >= 0) AND ($hora < 6)) {
$mensagem = "J&aacute; &eacute; madrugada";
} else if (($hora >= 6) AND ($hora < 12)) {
$mensagem = "Bom dia";
} else if (($hora >= 12) AND ($hora < 18)) {
$mensagem = "Boa tarde";
} else {
$mensagem = "Boa noite";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Financas</title>
		<link rel="stylesheet" type="text/css" href="conf/css/style.css" media="screen" />
       
		<link rel="stylesheet" type="text/css" href="conf/css/slide.css" media="screen" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

		<!-- PNG FIX for IE6 -->
		<!--[if lte IE 6]>
        <script type="text/javascript" src="login_panel/js/pngfix/supersleight-min.js"></script>
    <![endif]-->

		<script src="conf/js/slide.js" type="text/javascript"></script>
		<link href="conf/img/favicon.png" rel="icon" type="image/png"/>
		<script LANGUAGE="JavaScript" src="conf/js/scripts.js"></script>
		<script src="conf/js/jquery.js"></script>
		<script LANGUAGE="JavaScript" src="conf/js/jquery.validar.formulario.js"></script>
		<script>
		function showTimer() {
		var time=new Date();
		var hour=time.getHours();
		var minute=time.getMinutes();
		var second=time.getSeconds();
		if(hour<10)   hour  ="0"+hour;
		if(minute<10) minute="0"+minute;
		if(second<10) second="0"+second;
		var st=hour+":"+minute+":"+second;
		document.getElementById("timer").innerHTML = st; 
		}
		function initTimer() {

		/*setInterval(showTimer,1000);*/
		showTimer();
		}
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#formulario').validate({
					rules: {
						usuario: {
							required: true,
							minlength: 4
						},
						nome: {
							required: true
						},
						sobrenome: {
							required: true
						},
						senha: {
							required: true,
							minlength: 6
						},
						senhaconf: {
							required: true,
							equalTo: "#senha"
						},
					},
					messages: {
						usuario: {
							required: "Campo obrigat&oacute;rio.",
							minlength: "M&iacute;nimo 4 caracteres."
						},
						senha: {
							required: "Campo obrigat&oacute;rio.",
							minlength: "M&iacute;nimo 6 caracteres."
						},
						nome: {
							required: "Campo obrigat&oacute;rio."
						},
						sobrenome: {
							required: "Campo obrigat&oacute;rio."
						},
						senhaconf: {
							required: "Campo obrigat&oacute;rio.",
							equalTo: "Senhas n&atilde;o conferem."
						},
					}
				});
			});
		</script>
		<script>
		function passwordStrength(password)
		{
			var desc = new Array();
			desc[0] = "";
			desc[1] = "";
			desc[2] = "";
			desc[3] = "";
			desc[4] = "";
			desc[5] = "";

			var score   = 0;
			if (password.length > 6) score++;
			if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
			if (password.match(/\d+/)) score++;
			if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;
			if (password.length > 12) score++;
			document.getElementById("passwordDescription").innerHTML = desc[score];
			document.getElementById("passwordStrength").className = "strength" + score;
		}
		$(function(){
			$( "#usuario" ).keyup(function() {
				if ($("#usuario").val().length > 3)
					$("#usuario-error").empty();
			});
			$( "#senha" ).keyup(function() {
				if ($("#senha").val().length > 5)
					$("#senha-error").empty();
			});
		});
		
		</script>
		<style type="text/css">
		body {
	background-image: url(conf/img/fundo.jpg);
	background-repeat: no-repeat;
}
        </style>
		</head>
		<body onLoad="initTimer();">
<!-- Panel -->
<div id="toppanel">
          <div id="panel">
    <div class="content clearfix">
              <div class="left">
        <h1> Finanças </h1>
        <h2> Registo / Autenticação </h2>
        <!--<p class="grey">Estilizado por <a href="http://hardjunior.sytes.net" title="Entrar">Hardjunior</a>.</p>-->
        <!--<p class="grey">Estilizado por <a href="http://hardjunior.sytes.net" title="Entrar">Hardjunior</a>.</p>-->
      </div>
              <div class="left"> 
        <!-- Login Form -->
        <form method="post" action="valida.php">
                  <h1>Login de membro</h1>
                  <label class="grey" for="username">Usuário:</label>
                  <input type="text" class="form-control"  name="usuario" id="user_name"autocomplete='off' placeholder="Usuario">
                  <label class="grey" for="password">Senha:</label>
                  <input type="password" class="form-control" name="senha" id="password"autocomplete='off' placeholder="Senha" 
               data-placement="before">
                  <div class="clear"></div>
                  <input name="Login" type="submit" class="bt_login" id="Login" value="Entrar">
                </form>
      </div>
              <div class="left">
          <a href="javascript:;" onclick="abreFecha('cad_usuario');" title="Registar"   align="center" class="bt_register"> Registo</a><br>
          
    
          
          
          
        <a href="?logoff" class="bt_register">Sair</a> </div>
              <div  style="display:none;" id="cad_usuario" class="left"> 
        <!-- Register Form -->
        
        <form action="cadastro.php" method="post" class="welll" id="formulario" autocomplete="off">
                  <input type="hidden" name="acao" value="cadastrar" />
                  <br>
                  Usu&aacute;rio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="text" name="usuario" id="usuario" size="10" maxlength="15" minlength="3" />
                  <br>
                  Nome:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="text" name="nome" id="nome" size="20" maxlength="50" />
                  <br>
                  Sobrenome:
                  <input type="text" name="sobrenome" id="sobrenome" size="20" maxlength="50" />
                  <br>
                  Senha:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="password" name="senha" id="senha" onkeyup="passwordStrength(this.value)"/>
                  <br>
                  Confirmar:&nbsp;&nbsp;&nbsp;
                  <input type="password" name="senhaconf" id="senhaconf">
                  <br>
                  <!--<label for="passwordStrength"><font size=2>For&ccedil;a da senha</font></label>-->
                  <div id="passwordDescription"></div>
                  <div id="passwordStrength" class="strength0"></div>
                  <br />
                  <input type="submit" align="right" class="bt_login" value="Registar" />
                </form>
      </div>
            </div>
  </div>
          <!-- /login --> 
          
          <!-- The tab on top -->
          <div class="tab">
    <ul class="login">
              <li class="left">&nbsp;</li>
              <li><?php echo $mensagem?>! </li>
              <li class="sep">|</li>
              <li id="toggle"> <a id="open" class="open" href="#"><?php echo 'Abrir';?></a> <a id="close" style="display: none;" class="close" href="#">Fechar </a> </li>
              <li class="right">&nbsp;</li>
            </ul>
  </div>
          <!-- / top --> 
          
        </div> <div class="pageContent"><span class="tab"></span>
<!--panel -->

<?php /*?>
          <table width="30%" border="0" align="center" cellpadding="0">
  <tr>
    <th scope="col">&nbsp; <a href="javascript:;" style="font-size:16px; color:#FFFFFF" onclick="abreFecha('cad_usuario');" title="Registo" class="btn btn-success"  align="center"> Registar usu&aacute;rio</a><br></th>
  </tr>
</table>
<?php */?>
</div>
<div >
<div id="main">
  <div class="container">
    <!--<h1>Sistema de Autenticação</h1>-->
    <h1>Sistema de movimento Financeiro</h1>
	<h3><div class='footer' id='timer'></div></h3>
</div>
          <div class="container">
    <div>
              </form>
            </div>
    <div class="clear"></div>
    <div class="container tutorial-info"> <?php //echo "$desenvolvedor $versao"?> </div>
  </div>
        </div>
                      <center>
        <br>
        <?php echo " <img src='conf/img/adccl.png' width='100px' style='height:500px;width:600px;'> "?>
      </center>
</body>
</html>