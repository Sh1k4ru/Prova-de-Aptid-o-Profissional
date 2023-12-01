<?php

class DataAccess{
    private $connection;	
    
    function connect(){
        $bd= "sistLogin";
        $user = "root";
        $pwd = "";
        $server = "localhost";
		
        $this->connection = mysqli_connect($server, $user, $pwd, $bd);
        
        //verificar se a conexão está bem aberta        
        if( mysqli_select_db($this->connection, $bd) == false){
            //erro
            die("não conseguiu ligar-se à base de dados".mysqli_error($this->connection));            
			
        }else{
			
            mysqli_query($this->connection, "set names 'utf8'");
            mysqli_query($this->connection, "set character_set_connection=utf8");
            mysqli_query($this->connection, "set character_set_client=utf8");
            mysqli_query($this->connection, "set character_set_results=utf8");
        }        
    }
    
    function execute($query){
        $res = mysqli_query($this->connection, $query);
        if(!$res){
            die("Comando inválido".mysqli_error($this->connection));
        }else
            return $res;
    }
    
    function disconnect(){
		mysqli_close($this->connection);
    }
    
    function login($email, $password){
        $query = "select *
					from utilizadores 
					where email = '$email' and 
					password = '$password'";
        $this->connect();
        $res = $this->execute($query);
        $this->disconnect();
        return $res;
    }
	
	function inserirUtilizador($nome, $username, $email,
							$password){
		$query = "insert into utilizadores 
					(username, nome, idTipoUtilizador, 
					email, password)
					values 
					('$username','$nome',1,
					'$email','$password')";
		echo $query;
		$this->connect();
        $this->execute($query);
        $this->disconnect();
	}
	
	function editarUtilizador($id, $nome, $username, $email, $idTipoUtilizador){
		$query = "update utilizadores 
						set	
						nome = '$nome', 
						username = '$username',
						email = '$email',
						idTipoUtilizador = $idTipoUtilizador
						where id = $id";
		//echo $query;
		$this->connect();
        $this->execute($query);
        $this->disconnect();
	}
	
	function editarPerfilUtilizador($id, $nome, $username, $email){
		$query = "update utilizadores 
						set	
						nome = '$nome', 
						username = '$username',
						email = '$email'
						where id = $id";
		//echo $query;
		$this->connect();
        $this->execute($query);
        $this->disconnect();
	}
	
	function eliminarUtilizador($id){
		$query = "delete from utilizadores where id = $id";
		$this->connect();
        $this->execute($query);
        $this->disconnect();
	}
	
	function editarPasswordUtilizador($id, $oldPassword, $newPassword){
		//pesquisa - verificar se a password antiga está correta
		$query = "select password from utilizadores where id = $id ";
		$this->connect();
        $res = $this->execute($query);
        $row = mysqli_fetch_object($res);
		$erro = false;
		if ($oldPassword == $row->password){
			//se estiver correta, edita-a para a nova pwd
			$query = "update utilizadores set
							password  = '$newPassword'
							where id = $id";
			$this->execute($query);			
		}else{
			//else - dá erro!	
			$erro = true;
		}
		$this->disconnect();
		return $erro;
	}
	
	function getUtilizadores(){
		$query = "select U.*, TU.tipo 
						from utilizadores U 
							inner join tiposutilizadores TU
						where U.idTipoUtilizador = TU.id
						order by TU.tipo, U.nome desc
						";
		$this->connect();
        $res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
	
	function getUtilizador($id){
		$query = "select * from utilizadores where id = $id";
		$this->connect();
        $res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
	
	function getTiposUtilizadores(){
		$query = "select * from tiposutilizadores";
		$this->connect();
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
}
?>
