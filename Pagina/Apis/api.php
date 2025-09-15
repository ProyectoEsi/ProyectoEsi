<?php

require_once 'config.php';
require_once 'usuario.php';


$usuarioObj = new Usuario($conn);
$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_SERVER['PATH_INFO'];
header('Content-Type: application/json');

switch ($method) {
	case 'GET':
		if($endpoint === '/usuarios'){			
			$usuarios = $usuarioObj->getAllUsuarios();
			echo json_encode($usuarios);
		} elseif (preg_match('/^\/usuarios\/(\d+)$/', $endpoint, $matches)) {
			$cedulaSocio = $matches[1];
			$usuario = $usuarioObj->getUsuarioById($cedulaSocio);
			echo json_encode($usuario);
		} elseif ($endpoint === '/unidades') {
			$unidades = $usuarioObj->getUnidadesHabitacionales();
			echo json_encode($unidades);
		}
		break;
	case 'POST':
		if($endpoint === '/usuarios'){
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
			if ($result === 'not_accepted') {
				http_response_code(403);
				echo json_encode(['error' => 'Tu cuenta aún no ha sido aceptada por un administrador']);
			} elseif ($result != false) {
				echo json_encode(['success' => true, 'usuario' => $result]);
			} else {
				http_response_code(401);
				echo json_encode(['error' => 'Credenciales incorrectas']);
			}
		}elseif ($endpoint === '/admin-login') {
			$data = json_decode(file_get_contents('php://input'), true);
			$result = $usuarioObj->loginAdmin($data['Perfil'], $data['contrasena']);
			if ($result != false) {
				echo json_encode(['success' => true, 'admin' => $result]);
			} else {
				http_response_code(401);
				echo json_encode(['error' => 'Credenciales de administrador incorrectas']);
			}
		}elseif ($endpoint === '/unidades') {
			$data = json_decode(file_get_contents('php://input'), true);
			if (!isset($data['CeduladelSocio']) || !isset($data['NumeroDeHabitacion'])) {
				http_response_code(400);
				echo json_encode(['error' => 'Datos incompletos']);
				break;
			}
			$numero = (int)$data['NumeroDeHabitacion'];
			if ($numero < 1 || $numero > 100) {
				http_response_code(400);
				echo json_encode(['error' => 'El número de habitación debe ser entre 1 y 100']);
				break;
			}
			$result = $usuarioObj->assignUnidadHabitacional((int)$data['CeduladelSocio'], $numero);
			if ($result === true) {
				echo json_encode(['success' => true]);
			} else {
				http_response_code(400);
				echo json_encode(['error' => 'No se pudo asignar la unidad']);
			}
		}
		break;
	case 'PUT':
		if (preg_match('/^\/usuarios\/(\d+)$/', $endpoint, $matches)) {
			$cedulaSocio = $matches[1];
			$raw = file_get_contents('php://input');
			$data = json_decode($raw, true);
			if ($data === null) {
				parse_str($raw, $data);
			}
			$result = $usuarioObj->updateUsuario($cedulaSocio, $data);
			echo json_encode(['success' => $result]);
		}
		break;
	case 'DELETE':
		if (preg_match('/^\/usuarios\/(\d+)$/', $endpoint, $matches)) {
			$cedulaSocio = $matches[1];
			$result = $usuarioObj->deleteUsuario($cedulaSocio);
			echo json_encode(['success' => $result]);
		}
		break;
	default:
		header('Allow: GET, POST, PUT, DELETE');
		http_response_code(405);
		echo json_encode(['error' => 'Método no permitido']);
		break;
}
?>