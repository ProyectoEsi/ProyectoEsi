<?php
/* Clase usuario para gestionar con API RESTful
 * Permite operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * Requiere conexión a una base de datos MySQL
 * Actualizado para usar la tabla Socios en bd_LogicSpark
 */

// Configuracion del reporte de errores
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

class Usuario
{
	private $conn;

	// Constructor que recibe la conexión a la base de datos
	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	// Métodos para manejar usuarios (socios)
	// Obtener todos los socios
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
	
	// Obtener un socio por Cedula
	public function getUsuarioById($cedula)
	{
		$query = "SELECT * FROM Socios WHERE Cedula = $cedula";
		$result = mysqli_query($this->conn, $query);
		$usuario = mysqli_fetch_assoc($result);
		return $usuario;
	}
	
	// Agregar un nuevo socio
	public function addUsuario($data)
	{
		if(!isset($data['cedula']) || !isset($data['FotoDePerfil']) || !isset($data['Email']) || !isset($data['contrasena']) || !isset($data['Edad'])) {
			http_response_code(400);
			echo json_encode(["error" => "Datos incompletos"]);
		}else{
			$cedula = $data['cedula'];
			$email = $data['Email'];
			$edad = $data['Edad'];
			$contrasena = password_hash($data['contrasena'], PASSWORD_DEFAULT);
			$ntelefono = isset($data['Ntelefono']) ? $data['Ntelefono'] : null;
			$aporteInicial = isset($data['AporteInicial']) ? $data['AporteInicial'] : null;
			$aceptado = isset($data['Aceptado']) ? $data['Aceptado'] : false;
			
			// Procesar imagen base64
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
			
			$query = "INSERT INTO Socios (Cedula, Email, Edad, FotoDePerfil, contrasena, Ntelefono, AporteInicial, Aceptado) 
					  VALUES ('$cedula', '$email', '$edad', '$img_name', '$contrasena', " . 
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

	// Iniciar sesión de usuario
	public function loginUsuario($email, $contrasena)
	{
		$query = "SELECT * FROM Socios WHERE Email = '$email'";
		$result = mysqli_query($this->conn, $query);
		if(mysqli_num_rows($result) > 0){
			$usuario = mysqli_fetch_assoc($result);
			if(password_verify($contrasena, $usuario['contrasena'])){
				return $usuario; // Retorna el usuario si las credenciales son correctas
			} else {
				return false; // Contraseña incorrecta
			}
		} else {
			return false; // Usuario no encontrado
		}
	}

	// Actualizar un socio por Cedula
	public function updateUsuario($cedula, $data)
	{
		$email = $data['Email'];
		$nombreCompleto = isset($data['nombre_completo']) ? $data['nombre_completo'] : null;
		$edad = isset($data['Edad']) ? $data['Edad'] : null;
		$contrasena = password_hash($data['contrasena'], PASSWORD_DEFAULT);
		$ntelefono = isset($data['Ntelefono']) ? $data['Ntelefono'] : null;
		$aporteInicial = isset($data['AporteInicial']) ? $data['AporteInicial'] : null;
		$aceptado = isset($data['Aceptado']) ? $data['Aceptado'] : null;
		
		$query = "UPDATE Socios SET Email = '$email', contrasena = '$contrasena'";
		
		if($nombreCompleto !== null) $query .= ", NombreCompleto = '$nombreCompleto'";
		if($edad !== null) $query .= ", Edad = '$edad'";
		if($ntelefono !== null) $query .= ", Ntelefono = '$ntelefono'";
		if($aporteInicial !== null) $query .= ", AporteInicial = '$aporteInicial'";
		if($aceptado !== null) $query .= ", Aceptado = '$aceptado'";
		
		$query .= " WHERE Cedula = $cedula";
		
		$result = mysqli_query($this->conn, $query);
		if($result){
			return true;
		} else {
			return false;
		}
	}
	
	// Eliminar un socio por Cedula
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
}
?>