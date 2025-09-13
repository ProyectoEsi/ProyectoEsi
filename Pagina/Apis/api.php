<?php
/* API RESTful para gestionar socios
 * Permite operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * Actualizado para usar la nueva base de datos bd_LogicSpark
 */

// Importa las dependencias necesarias
require_once 'config.php';
require_once 'usuario.php';

// Crea la instance de la clase Usuario
$usuarioObj = new Usuario($conn);
// Obtiene el método de la solicitud HTTP
$method = $_SERVER['REQUEST_METHOD'];
// Obtiene el endpoint de la solicitud
$endpoint = $_SERVER['PATH_INFO'];
// Establece el tipo de contenido de la respuesta (json)
header('Content-Type: application/json');

// Procesa la solicitud según el método HTTP
switch ($method) {
	case 'GET':
		if($endpoint === '/usuarios'){
			// Obtiene todos los socios
			$usuarios = $usuarioObj->getAllUsuarios();
			echo json_encode($usuarios);
		} elseif (preg_match('/^\/usuarios\/(\d+)$/', $endpoint, $matches)) {
			// Obtiene un socio por Cedula
			$cedulaSocio = $matches[1];
			$usuario = $usuarioObj->getUsuarioById($cedulaSocio);
			echo json_encode($usuario);
		}
		break;
	case 'POST':
		if($endpoint === '/usuarios'){
			// Añade un nuevo socio
			$data = json_decode(file_get_contents('php://input'), true);
			$result = $usuarioObj->addUsuario($data);
			if ($result === true) {
				http_response_code(201);
				echo json_encode(['success' => $result]);
			}else{
				http_response_code(400);
				echo json_encode(['error' => 'Datos incompletos o error al registrar socio']);
			}
		}elseif ($endpoint === '/login') {
			$data = json_decode(file_get_contents('php://input'), true);
			$result = $usuarioObj->loginUsuario($data['Email'], $data['contrasena']);
			if ($result != false) {
				echo json_encode(['success' => true, 'usuario' => $result]);
			} else {
				http_response_code(401);
				echo json_encode(['error' => 'Credenciales incorrectas']);
			}
		}
		break;
	case 'PUT':
		if (preg_match('/^\/usuarios\/(\d+)$/', $endpoint, $matches)) {
			// Actualiza un socio por Cedula
			$cedulaSocio = $matches[1];
			parse_str(file_get_contents('php://input'), $data);
			$result = $usuarioObj->updateUsuario($cedulaSocio, $data);
			echo json_encode(['success' => $result]);
		}
		break;
	case 'DELETE':
		if (preg_match('/^\/usuarios\/(\d+)$/', $endpoint, $matches)) {
			// Elimina un socio por Cedula
			$cedulaSocio = $matches[1];
			$result = $usuarioObj->deleteUsuario($cedulaSocio);
			echo json_encode(['success' => $result]);
		}
		break;
	default:
		// Maneja métodos no permitidos
		header('Allow: GET, POST, PUT, DELETE');
		http_response_code(405);
		echo json_encode(['error' => 'Método no permitido']);
		break;
}
?>