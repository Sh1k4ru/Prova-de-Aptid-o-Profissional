<?php
	//quando o formulário for enviado - registar utilizador
	if(isset($_POST['botaoRegistar'])){
		//registar um utilizador
		$nome = $_POST['nome'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$pwd1 = md5($_POST['password1']);
		
		//utiliza a classe dataacess.php 
		include_once('DataAccess.php');
		//cria um objeto da classe 
		$da = new DataAccess();
		//chama a função inserirUtilizador da classe DataAccess
		$da->inserirUtilizador($nome, $username, $email, $pwd1);
		echo "<script>alert('Utilizador registado com sucesso')</script>";
		
	}else{
		//login
		if (isset($_POST['botaoEntrar'])){
			$email = $_POST['email'];
			$password = md5($_POST['password']);
			//utiliza a classe dataacess.php 
			include_once('DataAccess.php');
			//cria um objeto da classe 
			$andre = new DataAccess();
			//chama a função login da classe DataAccess
			$res = $andre->login($email, $password);
			if (mysqli_num_rows($res)>0){
				print "<script>alert('Login efetuado corretamente')</script>";	
				echo "<font color='green'>Login espetacular</font>";
				session_start();
				$row = mysqli_fetch_object($res);
				$_SESSION['id'] = $row->id;
				if ($row->idTipoUtilizador == 2)
					print "<script>window.location='inicio.php'</script>";
				else
					print "<script>window.location='perfil.php'</script>";	
			}else{
				echo "<script>alert('E-mail ou Password inválidos')</script>";				
				echo "<font color='red'>login error</font>"				;
			}
		}else{
			//Logout
			if (isset($_GET['l'])){
				if ($_GET['l']==1){
					session_start();
					session_destroy();
				}
			}
		}
	}
?>
<html>
	<head>
		<!-- Colocar página com codificação utf-8 -->
		<meta charset='utf-8' />
		
		<!-- colocar ícone da página no separador do browser -->
		<link rel="shortcut icon" href="logoEPBJC.png" type="image/png" />
		
		<script>
			function validarFormulario(){
				//vai buscar o email atraves do id do campo do formulário
				var email = document.getElementById('email');
				//permite validar a estrutura do email, verificando se tem @ e o .  		
  			    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;				
				
				if (!filter.test(email.value)) {
					alert('E-mail inválido');
					email.focus();
					return false;
				}else{
					//alert('email ok');				
					//validar se as password são iguais
					var pwd1 = document.getElementById('password1');
					var pwd2 = document.getElementById('password2');
					//verifica se as passwords são iguais 
					if (pwd1.value == pwd2.value && pwd1.value != ""){
						//alert('tudo ok');
						return true;						
					}else{
						alert('Password inválida');
						pwd1.focus();
						return false;
					}						
				}				
				return false;
			}
		</script>
		
	</head>
	<body>
		<!-- Formulário para registo de utilizador -->
		<div style='border:1px solid; width:33%; 
						position:absolute; left:33% '>
			<form method='post' action=''>
				Nome <input style="padding: 6px 10px; margin: 4px 0; " type='text' name='nome' id='nome' required/><br>
				Username <input style="padding: 6px 10px; margin: 4px 0; " type='text' name='username' id='username' required/><br>
				E-mail <input style="padding: 6px 10px; margin: 4px 0;" type='text' name='email' id='email' required/><br>
				Password <input style="padding: 6px 10px; margin: 4px 0; "  type='password' name='password1' id='password1' required/><br>
				Repita a password <input style="padding: 6px 10px; margin: 4px 0; "  type='password' name='password2' id='password2' required/><br><br>
				<!--Quando se clica no botão chama a fuunção validarFormulario do 
				javascript pata validar os campos -->
				<input type='submit' value='Registar' name='botaoRegistar' onclick='return validarFormulario()' /><br>
			</form>
		</div>
		
		
		<!-- Formulário para login -->
		<div style='border:1px solid; width:33%; 
						position:absolute; left:33%; top:45% '>
			<form method='post' action=''>
				E-mail <input style="padding: 6px 10px; margin: 4px 0; "  type='text' name='email' id='email' required/><br>
				Password <input style="padding: 6px 10px; margin: 4px 0; "  type='password' name='password' required/><br><br>
				<input type='submit' value='Entrar' name='botaoEntrar' /><br>
			</form>
		</div>
		
		
	</body>
</html>









