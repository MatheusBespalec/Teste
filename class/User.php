<?php

	class User{

		public static function userExists($user,$pass){
			$sql = MySql::conect()->prepare("SELECT * FROM `tb_admin.user` WHERE user = ? AND pass = ?");
			$sql->execute(array($_POST['user'],$_POST['pass']));

			if($sql->rowCount() == 1)
				return true;
			else
				return false;
		}

		public static function login($user,$pass){
			$_SESSION['login'] = true;
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;

			$sql = MySql::conect()->prepare("SELECT * FROM `tb_admin.user` WHERE user = ? AND pass = ?");
			$sql->execute(array($user,$pass));

			$img = $sql->fetch();

			$_SESSION['image'] = $img['img'];
		}

		public static function validImage($file){
			if ($file['type'] == 'image/jpeg' || $file['type'] == 'image/jpg' || $file['type'] == 'image/png') {
				$tamanho = intval($file['size']/1024);
				if ($tamanho > 1000) {
					alert('error','O tamanho da imagem é muito grande! Reduza ou utilize outra!');
					return false;
				}else{
					return true;
				}
			}else{
				alert('error','Formato de imagem invalido! Use apenas \'jpeg\',\'jpg\' e \'png\'');
				return false;
			}
		}

		public static function uploadImage($file,$user){
			$nome = explode('.', $file['name']);
			$count = count($nome)-1;
			$nomeImg = $user.'.'.$nome[$count];
			if(move_uploaded_file($file['tmp_name'], BASE_DIR.'/images/users/'.$nomeImg)){
				return $nomeImg;
			}else{
				alert('error','Erro ao subir imagem!');
				return false;
			}
		}

		public static function deleteImg($img){
			@unlink(BASE_DIR.'/images/users/'.$img);
		}
		

	}

?>