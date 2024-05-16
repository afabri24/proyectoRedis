<!DOCTYPE html>
<html lang="en">

<head>
    <title>Iniciar Sesión</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <a href="/agregar" class="btn btn-primary">Agregar +</a>
            <a href="/usuarios" class="btn btn-secondary">Usuarios</a>
            <a href="/logout" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <ul class="list-group">
            @foreach ($items as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item->name }}
                    <div>
                        <a href="/editar/{{ $item->id }}" class="btn btn-warning">Editar</a>
                        <a href="/eliminar/{{ $item->id }}" class="btn btn-danger">Eliminar</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</body>

</html>
