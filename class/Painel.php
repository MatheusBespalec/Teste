<?php

	class Painel{

		public static function login(){
			return isset($_SESSION['login']) ? true : false;
		}

		public static function loggout(){
			session_destroy();
		}

		public static function alert($status,$txt){
			echo '<div class="alert '.$status.'">'.$txt.'</div><!--alert-->' ;
		}

		public static function redirect($url){
			echo '<script>location.href="'.$url.'"</script>';
			die();
		}

		public static function loadPage(){
			if (isset($_GET['url'])) {
				$url = explode('/',$_GET['url']);

				if(file_exists('pages/'.$url[0].'.php'))
					include('pages/'.$url[0].'.php');
				else
					header('Location: '.INCLUDE_PATH);

			}else{
				include('pages/painel-de-controle.php');
			}
		}

		public static function getElement($table,$where,$arr,$item){
			$sql = MySql::conect()->prepare("SELECT * FROM `$table` WHERE $where");
			$sql->execute($arr);
			$el = $sql->fetch();

			return $el[$item];
		}

		public static function update($table,$arr,$where,$arr2){
			$first = true;
			$query = "UPDATE `$table` SET ";
			$el = [];
			foreach ($arr as $key => $value) {
				if($first){
					$query.="$key = ?";
					$first = false;
				}else{
					$query.=",$key = ?";
				}
				$el[] = $value;
			}

			foreach ($arr2 as $key => $value) {
				$el[] = $value;
			}
			
			$query.=" WHERE $where";
			$sql = MySql::conect()->prepare($query);
			if($sql->execute($el))
				return true;
			else
				return false;
		}

		public static function insert($table,$arr){
			$first = true;
			$query = "INSERT `$table` VALUES (";
			foreach ($arr as $key => $value) {
				if($first){
					$query.="?";
					$first = false;
				}else{
					$query.=",?";
				}
			}
			$query.=")";
			$sql = MySql::conect()->prepare($query);
			if($sql->execute($arr))
				return true;
			else{
				return false;
			}
		}

		public static function select($table,$query,$arr){
			$sql = MySql::conect()->prepare("SELECT * FROM `$table` $query");
			$sql->execute($arr);

			return $list = $sql->fetch();
		}

		public static function selectAll($table,$query,$arr){
			$sql = MySql::conect()->prepare("SELECT * FROM `$table` $query");
			$sql->execute($arr);

			return $list = $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public static function totalArr($arr,$column){
			$soma = 0;
			foreach ($arr as $key => $value) {
				$soma = $soma + $value[$column];
			}
			return $soma;
		}

		public static function getElements($table,$el){
			$sql = MySql::conect()->prepare("SELECT $el FROM `$table`");
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_ASSOC);

			foreach ($sql as $key => $value) {
				$count[] = $value['id'];
			}

			return $count;
		}

		public static function total($table,$el,$where=null){
			if($where == null)
				$sql = MySql::conect()->prepare("SELECT $el FROM `$table`");
			else
				$sql = MySql::conect()->prepare("SELECT $el FROM `$table` WHERE $where");

			$sql->execute();
			$sql = $sql->fetchAll();
			$total = 0;

			foreach ($sql as $key => $value) {
				$total = $total+$value[$el];
			}

			return $total;
		}

		public static function delete($table,$id){
			$sql = MySql::conect()->prepare("DELETE FROM `$table` WHERE id = ?");
			$sql->execute(array($id));
		}

		public static function exists($table,$column,$val){
			$sql = MySql::conect()->prepare("SELECT $column FROM `$table`");
			$sql->execute();
			$list = $sql->fetchAll();
			$exists = false;

			foreach ($list as $key => $value) {
				if($value[$column] == $val)
					$exists = true;
			}

			if($exists)
				return true;
			else
				return false;
		}

	}

?>