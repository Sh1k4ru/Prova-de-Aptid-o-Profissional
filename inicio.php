<?php session_start(); 


	if (isset($_SESSION['id'])){
		//nada!
		//é administrador?
		$id = $_SESSION['id'];
		include_once('DataAccess.php');//Inclui o ficheiro dataAccess
		$da = new DataAccess();//
		$res = $da->getUtilizador($id);
		$row = mysqli_fetch_object($res);
		if ($row->idTipoUtilizador != 2){//se não for addmin
			echo "<script>alert('Não tem permissões de administração');
						window.location='perfil.php'</script>";						
		}
		
		if (isset($_POST['botaoEditar'])){
			$nome =$_POST['nome'];
			$username =$_POST['username'];
			$email =$_POST['email'];
			$id =$_POST['id'];
			$idTipoUtilizador = $_POST['tipoUtilizador'];
			
			include_once('DataAccess.php');
			$da = new DataAccess();
			$da->editarUtilizador($id, $nome, $username, $email, $idTipoUtilizador);
			echo "<script>alert('Utilizador editado com sucesso')</script>";
		}else{
			if (isset($_GET['dl'])){
				$id = $_GET['dl'];
				include_once('DataAccess.php');
				$da = new DataAccess();
				$da->eliminarUtilizador($id);
				echo "<script>alert('Utilizador eliminado com sucesso')</script>";
			}
		}
		
		//se sim, tudo ok
		//se não, volta para a página index
	}else{
		
		echo "<script>window.location='index.php'</script>";		
	}
?>

<html>
	<head>
		<script>
			function TemACerteza(){
				return confirm('Tem a certeza que quer fazer logout?');
			}
			
			function TemACertezaEliminar(){
				return confirm('Tem a certeza que deseja eliminar este utilizador?');
			}
			
			function validarFormulario(){
				var email = document.getElementById('email');
  			    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;				
				
				if (!filter.test(email.value)) {
					alert('E-mail inválido');
					email.focus();
					return false;
				}else{
					return true;
				}
				
				return false;
			}
		
		</script>
	</head>
	<body>
		<a href='index.php?l=1' onclick='return TemACerteza()'>Sair</a>
		
		<table>
			<tr>
				<td>id</td>
				<td>nome</td>
				<td>e-mail</td>
				<td>username</td>
				<td>Tipo de Utilizador</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php
				include_once('DataAccess.php');
				$da = new DataAccess();
				$res = $da->getUtilizadores();
				while($row = mysqli_fetch_object($res)){
				echo "<tr>
						<td>$row->id</td>
						<td>$row->nome</td>
						<td>$row->email</td>
						<td>$row->username</td>
						<td>$row->tipo</td>
						<td>
							<a href='inicio.php?ed=$row->id'>
								<img src='imagens/editUser.png' title='Editar Utilizador' alt='Editar Utilizador' style='width:30px' />
							</a>
						</td>
						<td>
							<a href='inicio.php?dl=$row->id' onclick='return TemACertezaEliminar()'>
								<img src='imagens/deleteUser.png' title='Eliminar Utilizador' alt='Eliminar Utilizador' style='width:30px' />
							</a>
						</td>
					</tr>";
				}
			?>
		</table>
		
		<?php
			if (isset($_GET['ed'])){
				//mostra formulário para editar utilizador
				$id = $_GET['ed'];
				include_once('DataAccess.php');
				$da = new DataAccess();
				$res = $da->getUtilizador($id);
				$row = mysqli_fetch_object($res);
				echo "
					<div style='border:1px DOTTED; width:33%; 
						position:absolute; left:33% '>
						<form method='post' action='inicio.php'>
							<legend>Editar Utilizador</legend>
							<fieldset>
							<input type='hidden' name='id' value='$row->id'/>
							Nome<input type='text' value='$row->nome' name='nome' id='nome' required/><br/>
							Username<input type='text' name='username' id='username' required value='$row->username'/><br/>
							E-mail<input type='text' name='email' id='email' required value='$row->email'/><br/>
							Tipo de Utilizador
							<select name='tipoUtilizador'>
								";
								$res1 = $da->getTiposUtilizadores();
								while($row1 = mysqli_fetch_object($res1)){
									if ($row->idTipoUtilizador == $row1->id)
										echo "<option value='$row1->id' selected>$row1->tipo</option>";
									else
										echo "<option value='$row1->id'>$row1->tipo</option>";
								}
								echo "
							</select><br/>
							<input type='submit' value='Editar' name='botaoEditar' onclick='return validarFormulario()' />
							</fieldset>
						</form>
					</div>
				";
			}
		?>
	</body>
</html>







