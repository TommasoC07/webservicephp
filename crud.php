<?php
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Utenti API</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <div class="container mt-5">
        
        <div class="container mt-5">
            <h1>POST</h1>
       <form method ="post">
        <label for="nome">Nome</label><br>
        <input type="text"><br>
        <label for="nome">Email</label><br>
        <input type="email">
</form>
</div>

<div class="container mt-5">
            <h1>PUT</h1>
       <form method ="put">
        <label for="nome">Nome</label><br>
        <input type="text"><br>
        <label for="nome">Email</label><br>
        <input type="email">
</form>
</div>

<hr>
        </div>

        <!-- Area Risultati API -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Risultati dall'API</h5>
                        
                        <!-- Tabella per visualizzare i dati -->
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody id="lista-utenti">
                                <!-- I dati verranno inseriti qui da JavaScript -->
                            </tbody>
                        </table>

                        <!-- Messaggio di caricamento o errore -->
                        <div id="status-message" class="text-center mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <!-- Il tuo script per le chiamate API -->
    <script src="app.js"></script>
</body>
</html>


    
    

</body>
</html>
