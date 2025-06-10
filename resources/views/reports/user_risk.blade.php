<!DOCTYPE html>
<html>

<head>
    <title>Relatório de Risco</title>
</head>

<body>
    <h1>Análise de Risco - {{ $user->name }}</h1>

    <div>
        <p><strong>CPF:</strong> {{ $user->cpf }}</p>
        <p><strong>Status:</strong> {{ $user->financialProfile->situation->description }}</p>
        <p><strong>Nível de Risco:</strong> {{ $user->financialProfile->risk->description }}</p>
    </div>

    <h2>Dados de Endereço</h2>
    <p>{{ $user->address->street }}, {{ $user->address->city }}/{{ $user->address->state }}</p>
</body>

</html>