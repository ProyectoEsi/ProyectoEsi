<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

class Usuario
{
	private $conn;
	public function __construct($conn)
	{
		$this->conn = $conn;
	}
	public function getAllUsuarios()
	{
		$query = "SELECT * FROM Socios";
		$result = mysqli_query($this->conn, $query);
		$usuarios = [];
		while($row = mysqli_fetch_assoc($result)) {
			$usuarios[] = $row;
		}
		return $usuarios;
	}
	public function getUsuarioById($cedula)
	{
		$query = "SELECT * FROM Socios WHERE Cedula = $cedula";
		$result = mysqli_query($this->conn, $query);
		$usuario = mysqli_fetch_assoc($result);
		return $usuario;
	}
	public function addUsuario($data)
	{
		if(!isset($data['cedula']) || !isset($data['FotoDePerfil']) || !isset($data['Email']) || !isset($data['contrasena']) || !isset($data['Edad']) || !isset($data['nombre_completo'])) {
			http_response_code(400);
			echo json_encode(["error" => "Datos incompletos"]);
		}else{
			$cedula = $data['cedula'];
			$nombreCompleto = $data['nombre_completo'];
			$email = $data['Email'];
			$edad = $data['Edad'];
			$contrasena = password_hash($data['contrasena'], PASSWORD_DEFAULT);
			$ntelefono = isset($data['Ntelefono']) ? $data['Ntelefono'] : null;
			$aporteInicial = isset($data['AporteInicial']) ? $data['AporteInicial'] : null;
			$aceptado = isset($data['Aceptado']) ? $data['Aceptado'] : false;
			$img_data = $data['FotoDePerfil'];
			if (preg_match('/^data:image\/(\w+);base64,/', $img_data, $type)) {
				$img_data = substr($img_data, strpos($img_data, ',') + 1);
				$img_data = base64_decode($img_data);
				$ext = strtolower($type[1]);
				$img_name = uniqid() . "." . $ext;
				$img_path = __DIR__ . "/uploads/" . $img_name;
				if (!is_dir(__DIR__ . "/uploads/")) {
					mkdir(__DIR__ . "/uploads/", 0777, true);
				}
				if (file_put_contents($img_path, $img_data) === false) {
					http_response_code(500);
					echo json_encode(["error" => "No se pudo guardar la imagen"]);
					exit;
				}
			} else {
				http_response_code(400);
				echo json_encode(["error" => "Formato de imagen inválido"]);
				exit;
			}
			
			$query = "INSERT INTO Socios (Cedula, NombreCompleto, Email, Edad, FotoDePerfil, contrasena, Ntelefono, AporteInicial, Aceptado) 
					  VALUES ('$cedula', '$nombreCompleto', '$email', '$edad', '$img_name', '$contrasena', " . 
					  ($ntelefono ? "'$ntelefono'" : "NULL") . ", " . 
					  ($aporteInicial ? "'$aporteInicial'" : "NULL") . ", '$aceptado')";
			
			$result = mysqli_query($this->conn, $query);
			if($result){
				return true;
			} else {
				return false;
			}
		}
	}
	public function loginUsuario($email, $contrasena)
	{
		$query = "SELECT * FROM Socios WHERE Email = '$email'";
		$result = mysqli_query($this->conn, $query);
		if(mysqli_num_rows($result) > 0){
			$usuario = mysqli_fetch_assoc($result);
			if(password_verify($contrasena, $usuario['contrasena'])){
				$accepted = ($usuario['Aceptado'] === 1 || $usuario['Aceptado'] === '1' || strtolower((string)$usuario['Aceptado']) === 'true');
				if ($accepted) {
					return $usuario;
				} else {
					return 'not_accepted';
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function updateUsuario($cedula, $data)
	{
		$sets = [];
		if (isset($data['Email'])) {
			$email = $data['Email'];
			$sets[] = "Email = '$email'";
		}
		if (isset($data['contrasena'])) {
			$contrasena = password_hash($data['contrasena'], PASSWORD_DEFAULT);
			$sets[] = "contrasena = '$contrasena'";
		}
		if (isset($data['nombre_completo'])) {
			$nombreCompleto = $data['nombre_completo'];
			$sets[] = "NombreCompleto = '$nombreCompleto'";
		}
		if (isset($data['Edad'])) {
			$edad = $data['Edad'];
			$sets[] = "Edad = '$edad'";
		}
		if (isset($data['Ntelefono'])) {
			$ntelefono = $data['Ntelefono'];
			$sets[] = "Ntelefono = '$ntelefono'";
		}
		if (isset($data['AporteInicial'])) {
			$aporteInicial = $data['AporteInicial'];
			$sets[] = "AporteInicial = '$aporteInicial'";
		}
		if (isset($data['Aceptado'])) {
			$aceptado = $data['Aceptado'] ? 1 : 0;
			$sets[] = "Aceptado = '$aceptado'";
		}

		if (count($sets) === 0) {
			return false;
		}

		$query = "UPDATE Socios SET " . implode(", ", $sets) . " WHERE Cedula = $cedula";
		$result = mysqli_query($this->conn, $query);
		if($result){
			return true;
		} else {
			return false;
		}
	}
	public function deleteUsuario($cedula)
	{
		$query = "DELETE FROM Socios WHERE Cedula = $cedula";
		$result = mysqli_query($this->conn, $query);
		if($result){
			return true;
		} else {
			return false;
		}
	}

	public function loginAdmin($perfil, $contrasena)
	{
		$query = "SELECT * FROM Administradores WHERE Perfil = '$perfil'";
		$result = mysqli_query($this->conn, $query);
		if(mysqli_num_rows($result) > 0){
			$admin = mysqli_fetch_assoc($result);
			if(password_verify($contrasena, $admin['contrasena']) || $contrasena === $admin['contrasena']){
				return $admin;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function assignUnidadHabitacional($cedulaSocio, $numeroHabitacion)
	{
		$check = mysqli_query($this->conn, "SELECT Aceptado FROM Socios WHERE Cedula = " . (int)$cedulaSocio);
		if (!$check || mysqli_num_rows($check) === 0) {
			return false;
		}
		$row = mysqli_fetch_assoc($check);
		$accepted = ($row['Aceptado'] === 1 || $row['Aceptado'] === '1' || strtolower((string)$row['Aceptado']) === 'true');
		if (!$accepted) {
			return false;
		}
		$exists = mysqli_query($this->conn, "SELECT CeduladelSocio FROM UnidadHabitacional WHERE CeduladelSocio = " . (int)$cedulaSocio);
		if ($exists && mysqli_num_rows($exists) > 0) {
			$q = "UPDATE UnidadHabitacional SET NumeroDeHabitacion = '" . (int)$numeroHabitacion . "' WHERE CeduladelSocio = " . (int)$cedulaSocio;
			return mysqli_query($this->conn, $q) ? true : false;
		} else {
			$q = "INSERT INTO UnidadHabitacional (CeduladelSocio, NumeroDeHabitacion) VALUES ('" . (int)$cedulaSocio . "', '" . (int)$numeroHabitacion . "')";
			return mysqli_query($this->conn, $q) ? true : false;
		}
	}

	public function getUnidadesHabitacionales()
	{
		$query = "SELECT CeduladelSocio, NumeroDeHabitacion FROM UnidadHabitacional";
		$result = mysqli_query($this->conn, $query);
		$unidades = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$unidades[] = $row;
		}
		return $unidades;
	}
}
?>