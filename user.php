<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

try {
    $conn = new PDO("mysql:host=localhost;dbname=utenti;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents("php://input"));

    switch ($method) {

        case 'GET':
            $stmt = $conn->query("SELECT * FROM utente ORDER BY id DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'POST':
            if (empty($data->nome) || empty($data->email)) {
                http_response_code(400);
                echo json_encode(["error" => "Nome e email obbligatori"]);
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO utente (nome, email) VALUES (?, ?)");
            $stmt->execute([$data->nome, $data->email]);

            echo json_encode(["message" => "Utente creato"]);
            break;


        case 'PUT':
            if (empty($data->id)) {
                http_response_code(400);
                echo json_encode(["error" => "ID mancante"]);
                exit;
            }

            $fields = [];
            $values = [];

            if (!empty($data->nome)) {
                $fields[] = "nome = ?";
                $values[] = $data->nome;
            }

            if (!empty($data->email)) {
                $fields[] = "email = ?";
                $values[] = $data->email;
            }

            if (empty($fields)) {
                echo json_encode(["message" => "Nessun dato da aggiornare"]);
                exit;
            }

            $values[] = $data->id;

            $sql = "UPDATE utente SET " . implode(", ", $fields) . " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);

            echo json_encode(["message" => "Utente aggiornato"]);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? ($data->id ?? null);

            if (!$id) {
                http_response_code(400);
                echo json_encode(["error" => "ID mancante"]);
                exit;
            }

            $stmt = $conn->prepare("DELETE FROM utente WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(["message" => "Utente eliminato"]);
            break;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
