<?php
include ("./conf/config.php");
protegePagina();
include './conf/functions.php';
require_once './conf/versao.php';
$usuario = $_SESSION['usuarioID'];

//Apagar movimentos
if (isset($_GET['acao']) && $_GET['acao'] == 'apagar') {
    $id = $_GET['id'];
    $log = mysqli_query($_SG['link'], "SELECT * FROM movimentos WHERE id='$id'");
    $logexc = mysqli_fetch_array($log);
    $idmov = $logexc['id'];
    $tipomov = $logexc['tipo'];
    $descmov = $logexc['descricao'];
    $valormov = $logexc['valor'];
    $catmov = $logexc['cat'];
    $contamov = $logexc['conta'];
    $dataexc = date("Ymd");
	$id_comp_img=$logexc['comp_img'];

    mysqli_query($_SG['link'],"INSERT INTO exclusoes (id_mov_exc,tipo_mov,desc_mov,valor_mov,cat_mov,conta_mov,data_exc,usuario_mov) values ('$idmov','$tipomov','$descmov','$valormov','$catmov','$contamov','$dataexc','$usuario')");
    mysqli_query($_SG['link'], "DELETE FROM movimentos WHERE id='$id'");
    mysqli_query($_SG['link'], "DELETE FROM historico WHERE id_mov='$id'");
	$qr1=mysqli_query($_SG['link'], "SELECT * FROM movimentos WHERE comp_img='$id_comp_img'");
	$row1=mysqli_fetch_array($qr1);
	if (empty($row1)){
		mysqli_query($_SG['link'], "DELETE FROM comprovantes WHERE id='$id_comp_img'");
	}
    header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&ok=2");
    exit();
}

//Editar categorias
if (isset($_POST['acao']) && $_POST['acao'] == 'editar_cat') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];

    mysqli_query($_SG['link'], "UPDATE categorias SET nome='$nome' WHERE id='$id'");
    echo mysqli_error($_SG['link']);
    header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&cat_ok=3");
    exit();
}

//Apagar categorias
if (isset($_GET['acao']) && $_GET['acao'] == 'apagar_cat') {
    $id = $_GET['id'];

    $qr = mysqli_query($_SG['link'],
        "SELECT c.id FROM movimentos g, categorias c WHERE c.id=g.cat && c.id=$id");
    if (mysqli_num_rows($qr) !== 0) {
        header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&cat_err=1");
        exit();
    } else {
        mysqli_query($_SG['link'], "DELETE FROM categorias WHERE id='$id'");
        echo mysqli_error($_SG['link']);
        header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&cat_ok=2");
        exit();
    }
}

//Editar movimentos
if (isset($_POST['acao']) && $_POST['acao'] == 'editar_mov') {
	$file_tmp = $_FILES["file"]["tmp_name"];
	$file_name = $_FILES["file"]["name"];
	$file_type = $_FILES["file"]["type"];
	$file_size = $_FILES["file"]["size"];
	$dataimagen=date('dmyHi');
	$nome_r2="$usuario.$dataimagen.$file_name";
	$caminho="./upload_temp/$usuario.$dataimagen.$nome_r2";
	$extensao = @strtolower(end(explode('.',$file_name)));
	$extesoespermitidas= array('png','jpeg','jpg','bmp','pdf','doc','docx','xls','xlsx','html','xml','rar','zip');
	$tamanhoemBytes=@round (($file_size / 1024) / 1024,2);
	$tamanhoemMB=$tamanhoemBytes." MB";
    $id = $_POST['id'];
    $dia = $_POST['dia'];
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];
    $tipo = $_POST['tipo'];
    $cat = $_POST['cat'];
    $conta_lan = $_POST['conta'];
    $descricao = $_POST['descricao'];
    $valor = str_replace(",", ".", $_POST['valor']);
    $dataed = date("Ymd");
    $qred = mysqli_query($_SG['link'], "SELECT * FROM movimentos WHERE id='$id'");
    $rowed = mysqli_fetch_array($qred);
	$comp_cad=$rowed['comp_img'];

if (empty($valor)) {
echo "<script>
alert('O campo VALOR é obrigatório, e precisa ser diferente de zero para editar.'); location.href='index.php'; historico.go(-1);
</script>";
exit();
}
    if ($dia != $rowed['dia']) {
        mysqli_query($_SG['link'], "UPDATE movimentos SET dia='$dia', mes='$mes', ano='$ano', tipo='$tipo', cat='$cat', conta='$conta_lan', descricao='$descricao', valor='$valor', edicao='Editado' WHERE id='$id'");
        mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','1','$dataed','1','$usuario')");
        echo mysqli_error($_SG['link']);
    }

    if ($mes != $rowed['mes']) {
        mysqli_query($_SG['link'], "UPDATE movimentos SET dia='$dia', mes='$mes', ano='$ano', tipo='$tipo', cat='$cat', conta='$conta_lan', descricao='$descricao', valor='$valor', edicao='Editado' WHERE id='$id'");
        mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','2','$dataed','1','$usuario')");
        echo mysqli_error($_SG['link']);
    }

    if ($ano != $rowed['ano']) {
        mysqli_query($_SG['link'], "UPDATE movimentos SET dia='$dia', mes='$mes', ano='$ano', tipo='$tipo', cat='$cat', conta='$conta_lan', descricao='$descricao', valor='$valor', edicao='Editado' WHERE id='$id'");
        mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','3','$dataed','1','$usuario')");
        echo mysqli_error($_SG['link']);
    }

    if ($tipo != $rowed['tipo']) {
        mysqli_query($_SG['link'], "UPDATE movimentos SET dia='$dia', mes='$mes', ano='$ano', tipo='$tipo', cat='$cat', conta='$conta_lan', descricao='$descricao', valor='$valor', edicao='Editado' WHERE id='$id'");
        mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','4','$dataed','1','$usuario')");
        echo mysqli_error($_SG['link']);
    }

    if ($cat != $rowed['cat']) {
        mysqli_query($_SG['link'], "UPDATE movimentos SET dia='$dia', mes='$mes', ano='$ano', tipo='$tipo', cat='$cat', conta='$conta_lan', descricao='$descricao', valor='$valor', edicao='Editado' WHERE id='$id'");
        mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','5','$dataed','1','$usuario')");
        echo mysqli_error($_SG['link']);
    }

    if ($descricao != $rowed['descricao']) {
        mysqli_query($_SG['link'], "UPDATE movimentos SET dia='$dia', mes='$mes', ano='$ano', tipo='$tipo', cat='$cat', conta='$conta_lan', descricao='$descricao', valor='$valor', edicao='Editado' WHERE id='$id'");
        mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','6','$dataed','1','$usuario')");
        echo mysqli_error($_SG['link']);
    }

    if ($valor != $rowed['valor']) {
        mysqli_query($_SG['link'], "UPDATE movimentos SET dia='$dia', mes='$mes', ano='$ano', tipo='$tipo', cat='$cat', conta='$conta_lan', descricao='$descricao', valor='$valor', edicao='Editado' WHERE id='$id'");
        mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','7','$dataed','1','$usuario')");
        echo mysqli_error($_SG['link']);
    }

    if ($conta_lan != $rowed['conta']) {
        mysqli_query($_SG['link'], "UPDATE movimentos SET dia='$dia', mes='$mes', ano='$ano', tipo='$tipo', cat='$cat', conta='$conta_lan', descricao='$descricao', valor='$valor', edicao='Editado' WHERE id='$id'");
        mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','8','$dataed','$conta_lan','$usuario')");
        echo mysqli_error($_SG['link']);
    }
    if (!empty($file_tmp)){
		if (array_search($extensao, $extesoespermitidas) === false) {
			echo "<script>
			alert('São permitidos apenas arquivos nestes formatos: PNG, JPEG, PNG, BMP, PDF, DOC, DOCX, XLS, XLSX, HTML, XML, ZIP e RAR.'); location.href='index.php';
			</script>";
			exit();
		}
		if ($file_size>7340032){ 
			echo "<script>
			alert('O arquivo é muito grande. Tamanho maxímo 7Mb.'); location.href='index.php';
			</script>";
			exit();
		}
		if (empty($comp_cad)){
			copy($file_tmp, "$caminho");
			$fp = fopen($caminho, "rb");
			$filename=fread($fp, $file_size);
			$filename=addslashes($filename);
			fclose($fp);
			mysqli_query($_SG['link'], "INSERT INTO comprovantes (comp, nome, tipo, ext, tamanho) values ('$filename','$nome','$file_type','$extensao','$tamanhoemMB')");
			unlink($caminho);
			
			$dados=mysqli_query($_SG['link'], "SELECT * FROM comprovantes WHERE id=(SELECT MAX(id) FROM comprovantes)");
			$dados2=mysqli_fetch_array($dados);
			$id_img=$dados2['id'];
			mysqli_query($_SG['link'], "UPDATE movimentos SET edicao='Editado', comp_img='$id_img' WHERE id='$id'");
			mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','9','$dataed','$conta_lan','$usuario')");

			header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&ok=1");
			exit();
		}
		
		copy($file_tmp, "$caminho");
		$fp = fopen($caminho, "rb");
		$filename=fread($fp, $file_size);
		$filename=addslashes($filename);
		fclose($fp);
		mysqli_query($_SG['link'], "UPDATE comprovantes SET comp='$filename', nome='$nome', tipo='$file_type', ext='$extensao', tamanho='$tamanhoemMB' WHERE id='$comp_cad'");
		unlink($caminho);
		mysqli_query($_SG['link'], "UPDATE movimentos SET edicao='Editado' WHERE id='$id'");
		mysqli_query($_SG['link'], "INSERT INTO historico (id_mov,just_id,data,conta_mov,usuario) values ('$id','9','$dataed','$conta_lan','$usuario')");

		header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&ok=1");
		exit();
	}
}
//Cadastrar categorias
if (isset($_POST['acao']) && $_POST['acao'] == 2) {
    $nome = $_POST['nome'];

    mysqli_query($_SG['link'], "INSERT INTO categorias (nome,usuario) values ('$nome','$usuario')");
    echo mysqli_error($_SG['link']);
    header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&cat_ok=1");
    exit();
}

//Lançar movimentos
if (isset($_POST['acao']) && $_POST['acao'] == 1) {
	$file_tmp = $_FILES["file"]["tmp_name"];
	$file_name = $_FILES["file"]["name"];
	$file_type = $_FILES["file"]["type"];
	$file_size = $_FILES["file"]["size"];
	$dataimagen=date('dmyHi');
	 
    $nome_r2 = str_replace(" ", "", $file_name);
	$nome="$usuario.$dataimagen.$nome_r2";
	$caminho="./upload_temp/$usuario.$dataimagen.$nome_r2";
	$extensao = @strtolower(end(explode('.',$file_name)));
	$extesoespermitidas= array('png','jpeg','jpg','bmp','pdf','doc','docx','xls','xlsx','html','xml','rar','zip');
	$tamanhoemBytes=@round (($file_size / 1024) / 1024,2);
	$tamanhoemMB=$tamanhoemBytes." MB";
    $data = $_POST['data'];
    $tipo = $_POST['tipo'];
    $cat = $_POST['cat'];
    $descricao = $_POST['descricao'];
    $valor_recebido = str_replace(".", "", $_POST['valor']);
    $valortotal = str_replace(",", ".", $valor_recebido);
    $parcelas = $_POST['parcelas'];
    $valor = @$valortotal / $parcelas;
    $t = explode("/", $data);
    $dia = $t[0];
    $mes = $t[1];
    $ano = $t[2];

if (empty($valor)){
echo "<script>
alert('O campo VALOR é obrigatório, e precisa ser diferente de zero.'); location.href='index.php'; historico.go(-1);
</script>";
exit;
}
	$n=1;
	if (!empty($file_tmp)){
		if (array_search($extensao, $extesoespermitidas) === false) {
			echo "<script>
			alert('São permitidos apenas arquivos nestes formatos: PNG, JPEG, PNG, BMP, PDF, DOC, DOCX, XLS, XLSX, HTML, XML, ZIP e RAR.'); location.href='index.php';
			</script>";
			exit();
		}
		if ($file_size>7340032){ 
			echo "<script>
			alert('O arquivo é muito grande. Tamanho maxímo 7Mb.'); location.href='index.php';
			</script>";
			exit();
		}
		copy($file_tmp, "$caminho");
		$fp = fopen($caminho, "rb");
		$filename=fread($fp, $file_size);
		$filename=addslashes($filename);
		fclose($fp);
		mysqli_query($_SG['link'], "INSERT INTO comprovantes (comp, nome, tipo, ext, tamanho) values ('$filename','$nome','$file_type','$extensao','$tamanhoemMB')");
		unlink($caminho);
		
	while ($n <= $parcelas) {
	$dados=mysqli_query($_SG['link'], "SELECT * FROM comprovantes WHERE id=(SELECT MAX(id) FROM comprovantes)");
	$dados2=mysqli_fetch_array($dados);
	$id_img=$dados2['id'];
    mysqli_query($_SG['link'], "INSERT INTO movimentos (dia,mes,ano,tipo,descricao,valor,cat,conta,nparcela,parcelas,usuario,comp_img) values ('$dia','$mes','$ano','$tipo','$descricao','$valor','$cat','1','$n','$parcelas','$usuario','$id_img')");

    header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&ok=1");
	if ($mes<=11){
	$mes++;}
	else{
	$mes = 1;
	$ano++;}
	$n++;
	}
	exit();
	}

	while ($n <= $parcelas) {
    mysqli_query($_SG['link'], "INSERT INTO movimentos (dia,mes,ano,tipo,descricao,valor,cat,conta,nparcela,parcelas,usuario) values ('$dia','$mes','$ano','$tipo','$descricao','$valor','$cat','1','$n','$parcelas','$usuario')");

    header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano'] . "&ok=1");
	if ($mes<=11){
	$mes++;}
	else{
	$mes = 1;
	$ano++;}
	$n++;
	}
	exit();
}

//Cadastrar orçamento
if (isset($_POST['acao']) && $_POST['acao'] == 'cad_orcamento') {
    $valor_recebido = str_replace(".", "", $_POST['valor']);
    $valor_orcamento = str_replace(",", ".", $valor_recebido);
    $tipo = $_POST['tipo'];
    $data = $_POST['data'];
    $t = explode("/", $data);
    $dia = $t[0];
    $mes = $t[1];
    $ano = $t[2];
    $valida_meses = 12 - $mes + 1;

    if (empty($valor_orcamento)) {
        echo "<script>
alert('O campo VALOR é obrigatório, e precisa ser diferente de zero.'); location.href='index.php'; historico.go(-1);
</script>";
        exit;
    }
    if ($tipo != 0) {
        mysqli_query($_SG['link'],
            "INSERT INTO orcamento (mes,ano,valor,conta,usuario) values ('$mes','$ano','$valor_orcamento','1','$usuario')");
        echo mysqli_error($_SG['link']);
        header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano']);
        exit();
    }
    $n = 1;
    while ($n <= $valida_meses) {
        mysqli_query($_SG['link'],
            "INSERT INTO orcamento (mes,ano,valor,conta,usuario) values ('$mes','$ano','$valor_orcamento','1','$usuario')");
        echo mysqli_error($_SG['link']);
        header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano']);
        if ($mes <= 11) {
            $mes++;
        }
        $n++;
    }
    exit();
}

//Editar orçamento
if (isset($_POST['acao']) && $_POST['acao'] == 'ed_orcamento') {
    $valor_recebido = str_replace(".", "", $_POST['valor']);
    $valor_orcamento = str_replace(",", ".", $valor_recebido);
    $tipo = $_POST['tipo'];
    $data = $_POST['data'];
    $t = explode("/", $data);
    $dia = $t[0];
    $mes = $t[1];
    $ano = $t[2];
    $valida_meses = 12 - $mes + 1;

    if (empty($valor_orcamento)) {
        echo "<script>
alert('O campo VALOR é obrigatório, e precisa ser diferente de zero.'); location.href='index.php'; historico.go(-1);
</script>";
        exit();
    }
    if ($tipo != 0) {
        mysqli_query($_SG['link'], "UPDATE orcamento SET valor='$valor_orcamento' WHERE mes='$mes' && conta='1' && usuario='$usuario'");
        echo mysqli_error($_SG['link']);
        header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano']);
        exit();
    }
    mysqli_query($_SG['link'], "UPDATE orcamento SET valor='$valor_orcamento' WHERE mes>=$mes && ano='$ano' && conta='1' && usuario='$usuario'");
    echo mysqli_error($_SG['link']);
    header("Location: ?mes=" . $_GET['mes'] . "&ano=" . $_GET['ano']);
    exit();
}

//Boas vindas em função da hora
$hora = date('G');
if (($hora >= 0) and ($hora < 5)) {
    $mensagem = "Já é madrugada";
} else
    if (($hora >= 5) and ($hora < 6)) {
        $mensagem = "Já esta amanhecendo";
    } else
        if (($hora >= 6) and ($hora < 12)) {
            $mensagem = "Bom dia";
        } else
            if (($hora >= 12) and ($hora < 18)) {
                $mensagem = "Boa tarde";
            } else {
                $mensagem = "Boa noite";
            }

            //Mês e ano hoje
            if (isset($_GET['mes']))
                $mes_hoje = $_GET['mes'];
            else
                $mes_hoje = date('m');

if (isset($_GET['ano']))
    $ano_hoje = $_GET['ano'];
else
    $ano_hoje = date('Y');

?>

<html>
<head>

<title id='titulo'>FINANCAS</title>
<link href="conf/img/favicon.png" rel="icon" type="image/png"/>
<meta name="LANGUAGE" content="Portuguese" />
<meta name="AUDIENCE" content="all" />
<meta name="RATING" content="GENERAL" />

<link href="conf/css/styles.css" rel="stylesheet" type="text/css" />
<link id="scrollUpTheme" rel="stylesheet" href="conf/css/image.css">
<link href="conf/css/styles.css" rel="stylesheet" type="text/css" />
<link href="conf/css/bootstrap-combobox.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="conf/css/calculadora.css">
<script LANGUAGE="JavaScript" src="conf/js/scripts.js"></script>
<script src="conf/js/jquery.js"></script>
<script src="conf/js/jquery.scroll.topo.js"></script>
<script src="conf/js/jquery.easing.js"></script>
<script src="conf/js/jquery.easing.compatibilidade.js"></script>
<script LANGUAGE="JavaScript" src="conf/js/jquery.validar.formulario.js"></script>
<script src="conf/js/jquery.calc.js"></script>
<script src="conf/js/jquery.calculadora.js"></script>
<script>
(function ($) {
$.getQuery = function (query) {
	query = query.replace(/[\[]/, '\\\[').replace(/[\]]/, '\\\]');
	var expr = '[\\?&]' + query + '=([^&#]*)';
	var regex = new RegExp(expr);
	var results = regex.exec(window.location.href);
		if (results !== null) {
		return results[1];
		} else {
		return false;
		}
	};
})(jQuery);

$(function () {

	$('.image-switch').click(function () {
	window.location = '?theme=image';
	});

	if ($.getQuery('theme') === 'image') {
	$(function () {
		$.scrollUp({
			animation: 'fade',
			activeOverlay: 'false',
			scrollImg: {
			active: true,
			type: 'background',
			src: './conf/img/topo.png'
			}
		});
	});
$('#scrollUpTheme').attr('href', './conf/css/image.css?1.1');
$('.image-switch').addClass('active');
} else {
	$(function () {
	$.scrollUp({
		animation: 'slide',
		activeOverlay: 'false'
		});
	});
$('#scrollUpTheme').attr('href', './conf/css/image.css?1.1');
$('.image-switch').addClass('active');
}
});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#formulario_lancamento').validate({
			rules: {
				valor: {
					required: true,
				},
				parcelas: {
					required: true,
					digits: true
				},
			},
			messages: {
				valor: {
					required: "Campo obrigatório.",
				},
				parcelas: {
					required: "Campo obrigatório.",
					digits: "Digite apenas números."
				},
			}
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#form_alt_senha').validate({
			rules: {
				novasenha: {
					required: true,
					minlength: 6
				},
				novasenhaconf: {
					required: true,
					equalTo: "#novasenha"
				},
			},
			messages: {
				novasenha: {
					required: "Campo obrigatório.",
					minlength: "Mínimo 6 caracteres."
				},
				novasenhaconf: {
					required: "Campo obrigatório.",
					equalTo: "Senhas não conferem."
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
</script>
<script>
$(function () {
	$.calculator.setDefaults({showOn: 'both', buttonImageOnly: true, buttonImage: './conf/img/calc.png'});
	$('#valor').calculator(); //Calculadora comum para lançamento de movimentos
	$('#valororcamento').calculator({layout: $.calculator.scientificLayout}); //Calculadora cientifica para lançamento de orçamento
	$('#edorcamento').calculator({layout: $.calculator.scientificLayout}); //Calculadora cientifica para edição de orçamento
});
</script>


</head>
<body style="padding:10px">


<table  width="100%" align="center" cellpadding="1" cellspacing="5"   background="./conf/img/menubar_bg.gif" class="we">
<tr><td colspan="11"  align="center" valign="middle" >
<h2><a href=index.php style="color:#FFFF00"> Financeiro</a> |  <a href=./dashboard/dashboard.php style="color:#FFFFFF">Dashboard</a></h2>
</td>
<td colspan="2" align="right">
<a style="color:#FFF" href="?mes=<?php echo date('m')?>&ano=<?php echo date('Y')?>">Hoje:<strong> <?php echo date('d')?> de <?php echo mostraMes(date('m'))?> de <?php echo date('Y')?></strong></a>&nbsp; 
</td>
</tr>
<tr>
<td>
<select onChange="location.replace('?mes=<?php echo $mes_hoje?>&ano='+this.value)">
<?php
for ($i=2015;$i<=2020;$i++){
?>
<option value="<?php echo $i?>" <?php if ($i==$ano_hoje) echo "selected=selected"?> ><?php echo $i?></option>
<?php }?>
</select>
</td>
<?php
for ($i=1;$i<=12;$i++){
	?>
    <td align="center" style="<?php if ($i!=12) echo "border-right:1px solid #FFFFFF;"?> padding-right:5px">
    <a href="?mes=<?php echo $i?>&ano=<?php echo $ano_hoje?>" style="
    <?php if($mes_hoje==$i){?>    
    color:#448ED3; font-size:16px; font-weight:bold; background-color:#FFFFFF; padding:5px
    <?php }else{?>
    color:#FFFFFF;  font-size:16px;
    <?php }?>
    ">
    <?php echo mostraMes($i);?>
    </a>
    </td>
<?php
}
?>
</tr>
</table>

<table width="100%" align="center" cellpadding="5" cellspacing="0" class="well">
<tr>
<?php
$qrvisita = mysqli_query($_SG['link'], "SELECT * FROM usuarios where id='$usuario'");
$rowvisita = mysqli_fetch_array($qrvisita);

$qracesso = mysqli_query($_SG['link'], "SELECT * FROM usuarios where id='$usuario'");
$rowacesso = mysqli_fetch_array($qracesso);
$n = $rowacesso['n_acesso_f'];
$n_acesso = $n + 1;
?>
<td width="17%">
<b><font color="#000" size=1><?= ($n > 0)? "Último acesso: " . date('d/m/Y H:i:s', strtotime($rowvisita['ultimavisita'])):""; ?></font>
</td>
<td width="15%"><b><font color="#000" size=1><?="Acesso Nº: " ?><?= ($n = 0)?"1":"$n_acesso"; ?></font></td>
<td width="53%"><font color="#000"><?= $mensagem ." ".$_SESSION['usuarioNome']." ".$_SESSION['usuarioSobrenome']; ?>.</font></b>
</td>
<td width="15%" align="right" style="font-size:13px; color:rgba(4, 45, 191, 1)"><a href="javascript:;" style="font-size:12px; color:rgba(4, 45, 191, 1)" onClick="abreFecha('alterar_senha')"><img src="conf/img/senha.png" alt="Alterar senha" width="30" height="30"> </a>
<a href="logout.php" style="font-size:12px; color:rgba(4, 45, 191, 1)"><img src="conf/img/sair.png" alt="Sair" width="30" height="30"><?php echo
" " ?></a>
</td>
</tr>
<tr>
<td colspan="2">

</td>
</tr>
</table>

<table cellpadding="5" cellspacing="0" width="1000" align="center">
<tr style="display:none; background-color:#E0E0E0" id="alterar_senha">
<td align="left">
<form id="form_alt_senha" method="post" action="cadastro.php">
<input type="hidden" name="acao" value="alterar_senha" />
<input type="hidden" name="pagina" value="index.php" />
<input type="hidden" name="usuario" value="<?php echo $usuario ?>" />
<b>Nova senha:</b> <font color="#FF0000" size=2><input type="password" name="novasenha" id="novasenha" onKeyUp="passwordStrength(this.value)"></font> <b>Confirmar nova senha:</b> <font color="#FF0000" size=2><input type="password" name="novasenhaconf" id="novasenhaconf"></font><br>
<label for="passwordStrength"><font size=2>Força da senha</font></label><br>-->
<div id="passwordDescription"></div>
<div id="passwordStrength" class="strength0"></div>
<p align="right">
<input type="submit" class="input" value="Alterar" /></p>
</form>
</td>
</tr>
</table>
<table cellpadding="10" cellspacing="0" width="1000" align="center" >
  <tr>
<td colspan="2">
<h2><?php echo mostraMes($mes_hoje) ?>/<?php echo $ano_hoje ?></h2>
</td>
<td align="right">
<a href="javascript:;" onClick="abreFecha('add_cat')" class="bnt">[+] Adicionar Categoria</a>
<a href="javascript:;" onClick="abreFecha('add_movimento')" class="bnt"><strong>[+] Adicionar Movimento</strong></a>
</td>
</tr>
<tr >
<td colspan="3" >
<?php
     if (isset($_GET['cat_err']) && $_GET['cat_err'] == 1) {
?>
<div style="padding:5px; background-color:#FF6; text-align:center; color:#030">
<strong>Esta categoria não pode ser removida, pois há movimentos associados a mesma</strong>
</div>

<?php } ?>

<?php
if (isset($_GET['cat_ok']) && $_GET['cat_ok'] == 2) {
?>

<div style="padding:5px; background-color:#9CC; text-align:center; color:#000">
<strong>Categoria removida com sucesso!</strong>
</div>

<?php } ?>
    
<?php
if (isset($_GET['cat_ok']) && $_GET['cat_ok'] == 1) {
?>

<div style="padding:5px; background-color:#9CC; text-align:center; color:#000">
<strong>Categoria registada com sucesso!</strong>
</div>

<?php } ?>
    
<?php
if (isset($_GET['cat_ok']) && $_GET['cat_ok'] == 3) {
?>

<div style="padding:5px; background-color:#9CC; text-align:center; color:#000">
<strong>Categoria alterada com sucesso!</strong>
</div>
<?php } ?>

<?php
if (isset($_GET['ok']) && $_GET['ok'] == 1) {
?>

<div style="padding:5px; background-color:#9CC; text-align:center; color:#000">
<strong>Movimento registado com sucesso!</strong>
</div>

<?php } ?>

<?php
if (isset($_GET['ok']) && $_GET['ok'] == 2) {
?>

<div style="padding:5px; background-color:#900; text-align:center; color:#FFF">
<strong>Movimento removido com sucesso!</strong>
</div>

<?php } ?>
    
<?php
if (isset($_GET['ok']) && $_GET['ok'] == 3) {
?>

<div style="padding:5px; background-color:#9CC; text-align:center; color:#000">
<strong>Movimento alterado com sucesso!</strong>
</div>
<?php } ?>

<div style=" background-color:#F1F1F1; padding:10px; border:1px solid #999; margin:5px; display:none" id="add_cat">
<h3>Adicionar Categoria</h3>

<table width="100%">
<tr>
<td valign="top">
<form method="post" action="?mes=<?php echo $mes_hoje ?>&ano=<?php echo $ano_hoje ?>">
<input type="hidden" name="acao" value="2" />
Nome: <input type="text" name="nome" size="20" maxlength="50" />
<br />
<br />

<input type="submit" class="input" value="Gravar" />
</form>
</td>
            <td valign="top" align="right">
                <b>Editar/Remover Categorias:</b><br/><br/>
<?php
$qr = mysqli_query($_SG['link'], "SELECT id, nome FROM categorias where usuario='$usuario' ORDER BY nome");
while ($row = mysqli_fetch_array($qr)) {
?>
                <div id="editar2_cat_<?php echo $row['id'] ?>"> <?php echo $row['nome'] ?>  
                    
                     <a style="font-size:10px; color:#666" onClick="return confirm('Tem certeza que deseja remover esta categoria?\nAtenção: Apenas categorias sem movimentos associados poderão ser removidas.')" href="?mes=<?php echo $mes_hoje ?>&ano=<?php echo $ano_hoje ?>&acao=apagar_cat&id=<?php echo $row['id'] ?>" title="Remover">[remover]</a> <a href="javascript:;" style="font-size:10px; color:#666" onClick="document.getElementById('editar_cat_<?php echo $row['id'] ?>').style.display=''; document.getElementById('editar2_cat_<?php echo $row['id'] ?>').style.display='none'" title="Editar">[editar]</a>
                    
                </div>
                <div style="display:none" id="editar_cat_<?php echo $row['id'] ?>">
                    
<form method="post" action="?mes=<?php echo $mes_hoje ?>&ano=<?php echo $ano_hoje ?>">
<input type="hidden" name="acao" value="editar_cat" />
<input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
<input type="text" name="nome" value="<?php echo $row['nome'] ?>" size="20" maxlength="50" />
<input type="submit" class="input" value="Alterar" />
</form> 
                </div>

<?php } ?>

            </td>
        </tr>
    </table>
</div>

<div style=" background-color:#F1F1F1; padding:10px; border:1px solid #999; margin:5px; display:none" id="add_movimento">
<h3><b>Adicionar Movimento</b></h3>
<?php
$qr = mysqli_query($_SG['link'], "SELECT id, nome FROM categorias where usuario='$usuario' ORDER BY nome"); 
if (mysqli_num_rows($qr) == 0) echo "Adicione ao menos uma categoria";

else {
?>
<form id="formulario_lancamento" enctype="multipart/form-data" method="post" action="?mes=<?php echo $mes_hoje ?>&ano=<?php echo $ano_hoje ?>">
<input type="hidden" name="acao" value="1" />
<strong>Data: </strong>
<input type="text" name="data" size="11" maxlength="10" value="<?php echo date('d') ?>/<?php echo date('m') ?>/<?php echo date('Y') ?>" /> &nbsp;  |  &nbsp;
<strong>Tipo:</strong>
<label for="tipo_receita" style="color:rgba(4, 45, 191, 1)"><input type="radio" name="tipo" value="1" id="tipo_receita" /> Receita</label>&nbsp; 
<label for="tipo_despesa" style="color:#C00"><input type="radio" name="tipo" value="0" checked id="tipo_despesa" /> Despesa</label> &nbsp;  |  &nbsp;
<strong>Categoria:</strong>
<select name="cat">
<?php
    while ($row = mysqli_fetch_array($qr)) {
?>
<option value="<?php echo $row['id'] ?>"><?php echo $row['nome'] ?></option>
<?php } ?>
</select>

<br />
<br />

<strong>Descrição:</strong><br />
<input type="text" name="descricao" size="100" maxlength="255" />
<br />
<br />

<font color="#000" size=1>Obs.: Em gastos parcelados, deve ser informado o valor total da compra, <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e não o valor da parcela.</font><br />
<strong>Valor:</strong> € <font color="#FF0000" size=2><input type=text name=valor id="valor" length=15 onKeyPress="return(FormataReais(this,'.',',',event))"></font>
&nbsp;  |  &nbsp;
<strong>Parcelas:</strong>
<font color="#FF0000" size=2><input type="text" value="1" name="parcelas" size="2" maxlength="4" id="parcelas"/></font>&nbsp;  |  &nbsp;

<strong>Comprovante: </strong><input id="file" name="file" type="file" />

<br />
<br />
<center>
<input type="submit" class="input" value="Gravar" />
</center>
</form>
<?php } ?>
</div>
</td>
</tr>

<tr>
<td align="left" valign="top" width="450" style="background-color:#D3FFE2" class="welll">
 

<?php
$qr = mysqli_query($_SG['link'], "SELECT SUM(valor) as total FROM movimentos WHERE tipo=1 && conta=1 && usuario='$usuario' && mes='$mes_hoje' && ano='$ano_hoje'");
$row = mysqli_fetch_array($qr);
$entradas = $row['total'];

$qr = mysqli_query($_SG['link'], "SELECT SUM(valor) as total FROM movimentos WHERE tipo=0 && conta=1 && usuario='$usuario' && mes='$mes_hoje' && ano='$ano_hoje'");
$row = mysqli_fetch_array($qr);
$saidas = $row['total'];

$resultado_mes = $entradas - $saidas;
?>

    <fieldset>
        <legend><strong>Balanço Mensal</strong></legend>
        <table cellpadding="0" cellspacing="0" width="100%" class="welll">
            <tr>
                <td><span style="font-size:18px; color:#000">Entradas:</span></td>
                <td align="right"><span style="color:rgba(4, 45, 191, 1); font-size:18px"><?php echo formata_dinheiro($entradas) ?></span></td>
            </tr>
            <tr>
                <td><span style="font-size:18px; color:#000">Saídas:</span></td>
                <td align="right"><span style="font-size:18px; color:#C00"><?php echo formata_dinheiro($saidas) ?></span></td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr size="1" />
                </td>
            </tr>
            <tr>
                <td><strong style="font-size:22px; color:#000">Saldo Final:</strong></td>
                <td align="right"><strong style="font-size:22px; color:<?php if ($resultado_mes <0) echo "#C00"; else echo "rgba(4, 45, 191, 1)" ?>"><?php echo formata_dinheiro($resultado_mes) ?></strong></td>
            </tr>
        </table>
    </fieldset>

</td>

<td width="15">
</td>

<td align="left" valign="top" width="450" style="background-color:#F1F1F1" class="welll">
<fieldset>
<legend>Balanço Geral</legend>

<?php
$qr = mysqli_query($_SG['link'], "SELECT SUM(valor) as total FROM movimentos WHERE tipo=1 && conta=1 && usuario='$usuario'");
$row = mysqli_fetch_array($qr);
$entradas = $row['total'];

$qr = mysqli_query($_SG['link'], "SELECT SUM(valor) as total FROM movimentos WHERE tipo=0 && conta=1 && usuario='$usuario'");
$row = mysqli_fetch_array($qr);
$saidas = $row['total'];

$resultado_geral = $entradas - $saidas;
?>

<table cellpadding="0" cellspacing="0" width="100%" class="welll">
<tr>
<td><span style="font-size:19px; color:#000">Entradas:</span></td>
<td align="right"><span style="font-size:16px; color:rgba(4, 45, 191, 1)"><?php echo formata_dinheiro($entradas) ?></span></td>
</tr>
<tr>
<td><span style="font-size:19px; color:#000">Saídas:</span></td>
<td align="right"><span style="font-size:16px; color:#C00"><?php echo formata_dinheiro($saidas) ?></span></td>
</tr>
<tr>
<td colspan="2">
<hr size="1" />
</td>
</tr>
<tr>
<td><strong style="font-size:22px; color:#000">Saldo Final:</strong></td>
<td align="right"><strong style="font-size:22px; color:<?php if ($resultado_geral <0) echo "#C00"; else echo "rgba(4, 45, 191, 1)" ?>"><?php echo formata_dinheiro($resultado_geral) ?></strong></td>
</tr>
</table>

</fieldset>
</td>

</tr>
</table>
<br />
<table cellpadding="5" cellspacing="1" width="1000" align="center">
<tr><td>
<a href="javascript:;" style="font-size:17px; color:#0000FF" onClick="abreFecha('export_pdf');" title="Relatórios PDF"><img src="conf/img/pdf.png" width="64" height="64"></a>
</td>
<td align="right">&nbsp;</td>
</tr>
</table>
<table border="0" cellpadding="5" cellspacing="1" width="1000" align="center">
<tr style="display:none; background-color:#E0E0E0" id="export_pdf">
<td style="font-size:14px">
<b>
<center>Movimentos mensal</b><br>
Informe mês e ano desejados.</center>
<br>
<br>
<form method="post" action="./exportar/movimentos.php">
<input type="hidden" name="acao" value="movimentos" />
<input type="hidden" name="conta" value="1" />
<input type="hidden" name="nome" value="Conta Corrente" />
Mês: <input type="number" name="mes" size="2" maxlength="2" value="<?php echo $mes_hoje ?>" />
<br><br>
Ano: <input type="number" name="ano" size="4" maxlength="4" value="<?php echo $ano_hoje ?>" />
<br><br><center>
<input type="submit" class="input" value="Exportar" />
</form>
</td>
<td style="font-size:14px">
<b>
<center>Estatística mensal</b><br>
Informe mês e ano desejados.</center>
<br>
<br>
<form method="post" action="./exportar/estatistica.php">
<input type="hidden" name="acao" value="estatistica_mensal" />
<input type="hidden" name="nome" value="Conta Corrente" />
<input type="hidden" name="conta" value="1" />
Mês: <input type="number" name="mes" size="2" maxlength="2" value="<?php echo $mes_hoje ?>" />
<br><br>
Ano: <input type="number" name="ano" size="4" maxlength="4" value="<?php echo $ano_hoje ?>" />
<br><br><center>
<input type="submit" class="input" value="Exportar" />
</form>
</td>
<td style="font-size:14px">
<b>
<center>Estatística anual</b><br>
Informe o ano desejado.</center>
<br>
<br>
<form method="post" action="./exportar/estatistica.php">
<input type="hidden" name="acao" value="estatistica_anual" />
<input type="hidden" name="nome" value="Conta Corrente" />
<input type="hidden" name="conta" value="1" />
Ano: <input type="number" name="ano" size="4" maxlength="4" value="<?php echo $ano_hoje ?>" />
<br><br><br><br><center>
<input type="submit" class="input" value="Exportar" />
</form>
</td>
<td style="font-size:14px">
<b>
<center>Exclusões de movimentos</b><br>
Listar exclusões desta conta.</center>
<form method="post" action="./exportar/exclusoes.php">
<input type="hidden" name="conta" value="1" />
<input type="hidden" name="nome" value="Conta Corrente" />
<br><br><br><br><br><br><center><input type="submit" class="input" value="Exportar" />
</form>
</td>
</tr>
</table>
<table cellpadding="5" cellspacing="0" width="1000" align="center">
<tr>
<td align="right">
<hr size="1" />
</td>
</tr>
</table>
<table cellpadding="5" cellspacing="0" width="1000" align="center">
<tr>
<td colspan="3">
    <div style="float:right; text-align:right">
<form name="form_filtro_cat" method="get" action=""  >
<input type="hidden" name="mes" value="<?php echo $mes_hoje ?>" >
<input type="hidden" name="ano" value="<?php echo $ano_hoje ?>" >
    <b>Filtrar por categoria:</b>  <select name="filtro_cat" onChange="form_filtro_cat.submit()">
<option value="">Tudo</option>
<?php
$qr = mysqli_query($_SG['link'], "SELECT DISTINCT c.id, c.nome, c.usuario FROM categorias c, movimentos m WHERE m.cat=c.id && c.usuario='$usuario' && m.mes=$mes_hoje && m.ano=$ano_hoje && m.conta=1 ORDER BY c.nome");
while ($row = mysqli_fetch_array($qr)) {
?>
<option <?php if (isset($_GET['filtro_cat']) && $_GET['filtro_cat'] == $row['id']) echo "selected=selected" ?> value="<?php echo $row['id'] ?>"><?php echo $row['nome'] ?></option>
<?php } ?>
</select>
</form>
    </div>

<h2>Movimentos deste mês</h2>

</td>
<tr>
<tr style="background-color:#E0E0E0">
<td align="center" width="15"><b><?php echo "Dia" ?></td>
<td><b><?php echo "Descrição e categoria" ?></td>
<td align="center"><b><?php echo "Valor" ?></td>
</tr>
<?php
$filtros = "";
if (isset($_GET['filtro_cat'])) {
    if ($_GET['filtro_cat'] != '') {
        $filtros = "&& cat='" . $_GET['filtro_cat'] . "'";

        $qr = mysqli_query($_SG['link'],
            "SELECT SUM(valor) as total FROM movimentos WHERE tipo=1 && conta=1 && usuario='$usuario' && mes='$mes_hoje' && ano='$ano_hoje' $filtros");
        $row = mysqli_fetch_array($qr);
        $entradas = $row['total'];

        $qr = mysqli_query($_SG['link'],
            "SELECT SUM(valor) as total FROM movimentos WHERE tipo=0 && conta=1 && usuario='$usuario' && mes='$mes_hoje' && ano='$ano_hoje' $filtros");
        $row = mysqli_fetch_array($qr);
        $saidas = $row['total'];

        $resultado_mes = $entradas - $saidas;

    }
}

$qr = mysqli_query($_SG['link'],"SELECT * FROM movimentos WHERE conta=1 && usuario='$usuario' && mes='$mes_hoje' && ano='$ano_hoje' $filtros ORDER BY dia");
$cont = 0;
while ($row = mysqli_fetch_array($qr)) {
$cont++;

$cat = $row['cat'];
$qr2 = mysqli_query($_SG['link'], "SELECT nome FROM categorias WHERE id='$cat'");
$row2 = mysqli_fetch_array($qr2);
$categoria = $row2['nome'];

$comprovante=$row['comp_img'];
?>
<script>
$(function () {
	$.calculator.setDefaults({showOn: 'both', buttonImageOnly: true, buttonImage: './conf/img/calc.png'});
	$("#<?php echo $row['id'] ?>").calculator(); //Calculadora comum para edição de movimentos
});
</script>
<tr style="background-color:<?php if ($cont % 2 == 0)
        echo "#F1F1F1";
    else
        echo "#E0E0E0" ?>" >
<td align="center" width="15"><?php echo $row['dia'] ?></td>
<td><?php echo $row['descricao'] ?> <?php $parcelas = $row['parcelas']; $nparcelas = $row['nparcela']; if ($parcelas >= 2) echo "Parcela " . $nparcelas . "/" . $parcelas . "." ?> <em>(<a href="?mes=<?php echo $mes_hoje ?>&ano=<?php echo $ano_hoje ?>&filtro_cat=<?php echo $cat ?>"><?php echo $categoria ?></a>)</em><?php if (empty($comprovante)) echo ""; else echo "<a href=./upload_temp/download.php?id=$comprovante style=font-size:12px> [Comprovante]</a>"?> <a href="javascript:;" style="font-size:12px; color:#666" onClick="abreFecha('editar_mov_<?php echo $row['id'] ?>');" title="Editar">[editar]</a> <a href="javascript:;" style="font-size:12px; color:#666" onClick="abreFecha('hist_mov_<?php echo $row['id'] ?>');" title="Ver histórico"> [Histórico]</a><br>
</td>
<td align="right"><strong style="color:<?php if ($row['tipo'] == 0) echo "#C00"; else echo "rgba(4, 45, 191, 1)" ?>"><?php echo formata_dinheiro($row['valor']) ?></strong></td>
</tr>
    <tr style="display:none; background-color:<?php if ($cont % 2 == 0) echo "#F1F1F1"; else echo "#E0E0E0" ?>" id="editar_mov_<?php echo $row['id'] ?>">
        <td colspan="3">
            <hr/>

            <form enctype="multipart/form-data" method="post" action="?mes=<?php echo $mes_hoje ?>&ano=<?php echo $ano_hoje ?>">
            <input type="hidden" name="acao" value="editar_mov" />
            <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
                        
            <b>Dia:</b> <input type="text" name="dia" size="2" maxlength="2" value="<?php echo $row['dia'] ?>" />&nbsp;|&nbsp;
            <b>Mês:</b> <input type="text" name="mes" size="2" maxlength="2" value="<?php echo $row['mes'] ?>" />&nbsp;|&nbsp;
            <b>Ano:</b> <input type="text" name="ano" size="3" maxlength="4" value="<?php echo $row['ano'] ?>" />&nbsp;|&nbsp;
            <b>Tipo:</b> <label for="tipo_receita<?php echo $row['id'] ?>" style="color:rgba(4, 45, 191, 1)"><input <?php if ($row['tipo']==1) echo "checked=checked" ?> type="radio" name="tipo" value="1" id="tipo_receita<?php echo $row['id'] ?>" /> Receita</label>&nbsp; <label for="tipo_despesa<?php echo $row['id'] ?>" style="color:#C00"><input <?php if ($row['tipo']==0) echo "checked=checked" ?> type="radio" name="tipo" value="0" id="tipo_despesa<?php echo $row['id'] ?>" /> Despesa</label>&nbsp;&nbsp;&nbsp;|&nbsp;
            <b>Categoria:</b>
<select name="cat">
<?php
$qr2 = mysqli_query($_SG['link'], "SELECT * FROM categorias where usuario='$usuario' ORDER BY nome");
while ($row2 = mysqli_fetch_array($qr2)) {
?>
<option <?php if ($row2['id'] == $row['cat']) echo "selected" ?> value="<?php echo $row2['id'] ?>"><?php echo $row2['nome'] ?></option>
<?php } ?>
</select><br /><br />            
            <b>Descricao:</b> <input type="text" name="descricao" value="<?php echo $row['descricao'] ?>" size="100" maxlength="255" />
         <br /><br />
            <b>Valor:</b> €  <input type=text id="<?php echo $row['id'] ?>" value="<?php echo $row['valor'] ?>" name=valor length=15 onKeyPress="return(FormataReais(this,'.',',',event))">
			&nbsp; | <input type="hidden" name="conta" value="1" />
			<br><br>
			<strong>Comprovante: </strong><input id="file" name="file" type="file"/> &nbsp;  |  &nbsp;            
			<input type="submit" class="input" value="Gravar" />
            </form> 
            <div style="text-align: right">
            <a style="color:#FF0000" onClick="return confirm('Tem certeza que deseja apagar?')" href="?mes=<?php echo $mes_hoje ?>&ano=<?php echo $ano_hoje ?>&acao=apagar&id=<?php echo $row['id'] ?>" title="Remover">[remover]</a> 
            </div>
            <hr/>
        </td>
    </tr>
<tr style="display:none; background-color:<?php if ($cont % 2 == 0)
                    echo "#F1F1F1";
                else
                    echo "#E0E0E0" ?>" id="hist_mov_<?php echo
$row['id'] ?>">
<td align="center" width="15"></td>
<td>
<?php
                    $id = $row['id'];
    $hist = mysqli_query($_SG['link'], "SELECT * FROM historico WHERE id_mov = '$id' ORDER BY id");
    $qrhist = mysqli_query($_SG['link'],
        "SELECT j.just, h.data, h.id FROM (just_ed j INNER JOIN historico h ON j.id = h.just_id) INNER JOIN movimentos g ON h.id_mov = g.id && g.id = '$id' ORDER BY h.id");

    if (mysqli_num_rows($hist) !== 0) {
        echo "Histórico de alterações:" . "<br>";
        while ($rowhist = mysqli_fetch_array($qrhist)) {
            echo date('d/m/y', strtotime($rowhist['data'])) . "  -  " . $rowhist['just'] .
                "<br>";
        }
    } else {
        echo "Não há histórico de alterações.";
    }
?>
</td>
<td></td>
</tr>
   
<?php
}
?>
<tr>
<td colspan="3" align="right">
<strong style="font-size:22px; color:<?php if ($resultado_mes < 0)
    echo "#C00";
else
    echo "rgba(4, 45, 191, 1)" ?>"><?php echo
formata_dinheiro($resultado_mes) ?></strong>
</td>
</tr>
</table>

<table cellpadding="5" cellspacing="0" width="1000" align="center">
<tr>
<td align="right">
<hr size="1" />
<?php echo $versao ?>  
</td>
</tr>
</table>
</body>
</html>
