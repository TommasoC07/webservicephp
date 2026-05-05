<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");

$host = "localhost";
$db_name = "gestione_spese";
$username = "root";
$password ="";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["errore" => "Impossibile connettersi al database"]);
    exit;
}
$metodo=$_SERVER['REQUEST_METHOD'];

if($metodo =='GET') {
//in base a quali variabili sono settate, verrà prodotto un filtraggio diverso
    if(isset($_GET['cat_name'])) {
        $cat_name= $_GET['cat_name'];
        $sql = "SELECT spesa.*, categoria.nome FROM spesa INNER JOIN categoria ON spesa.cat_id = 
                categoria.id WHERE categoria.nome = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cat_name]);
 // conta quante spese sono state fatte per categoria       
    }elseif(isset($_GET['stats']) ){
        switch($_GET['stats']){
            
        case 'conteggio': $sql = "SELECT categoria.nome, COUNT(*) AS numero_spese FROM spesa INNER JOIN categoria ON spesa.cat_id = 
                categoria.id GROUP BY categoria.nome";
                $stmt= $pdo->query($sql);
                break;

        //somma tutte le spese fatte per categoria
    
        case 'somma': $sql = "SELECT categoria.nome, SUM(importo) AS spesa_totale FROM spesa INNER JOIN categoria ON spesa.cat_id = 
                categoria.id GROUP BY categoria.nome";
        $stmt = $pdo->query($sql);
        break;
        
         //mostra la media di tutte le spese fatte per categoria
    case 'media': $sql = "SELECT categoria.nome, AVG(importo) AS media_spese FROM spesa INNER JOIN categoria ON spesa.cat_id = 
                categoria.id GROUP BY categoria.nome ";
        $stmt = $pdo->query($sql);
        break;
        //calcola tutto
    case 'all': $sql = "SELECT AVG(importo) AS media_spese, SUM(importo) AS totale_spese, COUNT(*) AS spese_totali FROM spesa ";
                $stmt = $pdo->query($sql);
        break;
            case 'data': $sql = "SELECT * FROM spesa ORDER BY data_creazione";
            $stmt = $pdo->query($sql);
            break;
    default: echo json_encode(["errore" => "Nessun filtro selezionato"]);
    exit;
    }
    } else {
        //Se nell'url non viene indicato il filtro, il risultato sarà composto da tutte le spese
        $sql = "SELECT spesa.*, categoria.nome FROM spesa
                INNER JOIN categoria ON spesa.cat_id = categoria.id ORDER BY spesa.data_creazione DESC";
        $stmt = $pdo->query($sql);
    }
    
    $risultati = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($risultati);
}

if ($metodo == 'POST') {
    $input = file_get_contents("php://input");
    $dati=json_decode($input, true);
    
    if(isset($dati['tipo']) && $dati['tipo']=='spesa'){
    //controllo sui campi: se sono vuoti si annulla l'operazione
    if (!empty($dati['titolo'])&& !empty($dati['importo']) && !empty($dati['cat_id'])) {
        $sql = "INSERT INTO spesa (titolo, importo, cat_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dati['titolo'], $dati['importo'], $dati['cat_id'] ]);
        echo json_encode(["messaggio" => "Spesa inserita con successo"]);
        
    } else {
        echo json_encode(["errore" => "Dati mancanti per l'inserimento"]);
    }
    }elseif(isset($dati['tipo']) && $dati['tipo']=='categoria'){
        if(!empty($dati['nome'])) {
        $sql = "INSERT INTO categoria(nome) VALUES (?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dati['nome'] ]);
        echo json_encode(["messaggio" => "Categoria inserita con successo"]);
    }
 }else{
      echo json_encode(["errore" => "Nessun tipo selezionato"]);
 }
}

if($metodo == 'DELETE') {
    if(isset($_GET['tipo'])){
    if (isset($_GET['id'])) {
        $delete_id = $_GET['id'];
        $sql = "DELETE FROM spesa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
         $stmt->execute([$delete_id]);

        //controllo sulle righe eliminate
        if ($stmt->rowCount() > 0) {
            echo json_encode(["messaggio" => "Spesa eliminata correttamente"]);
        } else {
            echo json_encode(["errore" => "Nessuna spesa trovata con questo id"]);}
        } else {
        echo json_encode(["errore" => "Inserisci un id nell'URL"]);
    }
}else{
    if (isset($_GET['id'])) {
        $delete_id = $_GET['id'];
        $sql = "DELETE FROM categoria WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$delete_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["messaggio" => "Categoria eliminata correttamente"]);
        } else {
            echo json_encode(["errore" => "Nessuna categori trovata con questo id"]);}
        } else {
        echo json_encode(["errore" => "Inserisci un id nell'URL"]);
    }
}
}
// Se il metodo non è nessuno di questi, viene mostrato un errore
if ($metodo != 'GET' && $metodo != 'POST' && $metodo != 'DELETE') {
    echo json_encode(["errore" => "Metodo non supportato"]);
}//i commenti nel codice che ho scritto li ho inseriti io
?>
