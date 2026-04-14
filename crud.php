<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Utenti API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">

        <div class="col-md-6 mb-4">
            <div class="card p-4 shadow-sm">
                <h3>Nuovo Utente</h3>
                <form id="form-post">
                    <input type="text" id="post-nome" class="form-control mb-2" placeholder="Nome" required>
                    <input type="email" id="post-email" class="form-control mb-2" placeholder="Email" required>
                    <button class="btn btn-success w-100">Crea</button>
                </form>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card p-4 shadow-sm">
                <h3>Modifica Utente</h3>
                <form id="form-put">
                    <input type="number" id="put-id" class="form-control mb-2" placeholder="ID" required>
                    <input type="text" id="put-nome" class="form-control mb-2" placeholder="Nome">
                    <input type="email" id="put-email" class="form-control mb-2" placeholder="Email">
                    <button class="btn btn-primary w-100">Aggiorna</button>
                </form>
            </div>
        </div>

    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Lista Utenti</h5>
            <table class="table table-hover mt-3">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Azioni</th>
                </tr>
                </thead>
                <tbody id="lista-utenti"></tbody>
            </table>

            <div id="status-message" class="alert d-none"></div>
        </div>
    </div>
</div>

<script>
const API_URL = 'utente.php';


function mostraMessaggio(msg, tipo="success") {
    const el = document.getElementById('status-message');
    el.className = `alert alert-${tipo}`;
    el.innerText = msg;
    el.classList.remove('d-none');
}


function caricaUtenti() {
    fetch(API_URL + '?t=' + Date.now())
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('lista-utenti');
            tbody.innerHTML = '';

            data.forEach(user => {
                tbody.innerHTML += `
                <tr onclick="compilaForm(${user.id}, '${user.nome}', '${user.email}')">
                    <td>${user.id}</td>
                    <td>${user.nome}</td>
                    <td>${user.email}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); eliminaUtente(${user.id})">
                            Elimina
                        </button>
                    </td>
                </tr>`;
            });
        })
        .catch(() => mostraMessaggio("Errore caricamento", "danger"));
}


document.getElementById('form-post').onsubmit = function(e) {
    e.preventDefault();

    const payload = {
        nome: document.getElementById('post-nome').value,
        email: document.getElementById('post-email').value
    };

    fetch(API_URL, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        mostraMessaggio(res.message);
        this.reset();
        caricaUtenti();
    });
};

document.getElementById('form-put').onsubmit = function(e) {
    e.preventDefault();

    const payload = {
        id: document.getElementById('put-id').value,
        nome: document.getElementById('put-nome').value,
        email: document.getElementById('put-email').value
    };

    fetch(API_URL, {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        mostraMessaggio(res.message);
        this.reset();
        caricaUtenti();
    });
};


function eliminaUtente(id) {
    if(confirm("Eliminare utente " + id + "?")) {
        fetch(API_URL + '?id=' + id, { method: 'DELETE' })
            .then(res => res.json())
            .then(res => {
                mostraMessaggio(res.message, "warning");
                caricaUtenti();
            });
    }
}

function compilaForm(id, nome, email) {
    document.getElementById('put-id').value = id;
    document.getElementById('put-nome').value = nome;
    document.getElementById('put-email').value = email;
}

window.onload = caricaUtenti;
</script>

</body>
</html>
