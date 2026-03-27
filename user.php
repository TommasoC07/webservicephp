<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true);

$users = [
    ["id" => 1, "nome" => "Mario Rossi", "email" => "mario@example.com"],
    ["id" => 2, "nome" => "Luigi Verdi", "email" => "luigi@example.com"]
];

switch ($method) {
    case 'GET':
        echo json_encode($users);
        break;

    case 'PUT':
        if (isset($input['id'])) {
            $id = $input['id'];
        } else {
            $id = "Sconosciuto";
        }

        if (isset($input['nome'])) {
            $nome = $input['nome'];
        } else {
            $nome = "Senza Nome";
        }

        echo json_encode(["message" => "Utente $id aggiornato a $nome"]);
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } elseif (isset($input['id'])) {
            $id = $input['id'];
        } else {
            $id = "Nessun ID fornito";
        }

        echo json_encode(["message" => "Utente $id eliminato"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Metodo non supportato"]);
        break;
}
