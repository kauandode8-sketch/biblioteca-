<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<script>
    function cadastrarUsuario(event) {
        event.preventDefault();

        alert("Usuário cadastrado com sucesso!");

        window.location.href = "index.html";
    }
</script>
<body>
    ```html
<form onsubmit="cadastrarUsuario(event)">

    <div class="campo">
        <label for="nome">Nome completo</label>
        <input
            type="text"
            id="nome"
            name="nome"
            placeholder="Digite o nome completo"
            required
        >
    </div>

    <div class="campo">
        <label for="email">E-mail</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Digite o e-mail"
            required
        >
    </div>

    <div class="campo">
        <label for="telefone">Telefone</label>
        <input
            type="tel"
            id="telefone"
            name="telefone"
            placeholder="Digite o telefone"
            required
        >
    </div>

    <div class="campo">
        <label for="tipo">Tipo de usuário</label>

        <select id="tipo" name="tipo" required>
            <option value="">Selecione</option>
            <option value="aluno">Aluno</option>
            <option value="professor">Professor</option>
            <option value="funcionario">Funcionário</option>
        </select>
    </div>

    <button type="submit">
        Cadastrar usuário
    </button>

</form>


<script>
    function cadastrarUsuario(event) {

        event.preventDefault();

        alert("Usuário cadastrado com sucesso!");

        window.location.href = "index.html";
    }
</script>
```

    
</body>
</html>