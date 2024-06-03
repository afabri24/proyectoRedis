<!DOCTYPE html>
<html lang="en">

<head>
    <title>Iniciar Sesión</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container">
        <!-- Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Agregar ítem</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addItemForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="itemName" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="itemName" required>
                            </div>
                            <div class="mb-3">
                                <label for="itemSound" class="form-label">Sonido</label>
                                <input type="file" class="form-control" id="itemSound" accept="audio/*" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveItemButton">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">Agregar +</a>
            <a href="/usuario" class="btn btn-secondary">Usuarios</a>
            <a href="/usuario/logout" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <ul class="list-group">
            @foreach ($items as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <button class="btn btn-info play-sound" data-sound="{{ $item->sound }}">Reproducir</button>
                    {{ $item->name }}
                    <div>
                        <button class="btn btn-danger delete-item" data-id="{{ $item->id }}">Eliminar</button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        document.querySelectorAll('.play-sound').forEach(function(button) {
            button.addEventListener('click', function() {
                var soundData = this.getAttribute('data-sound');

                // Convert the base64 string back to a blob
                var byteCharacters = atob(soundData);
                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var blob = new Blob([byteArray], {
                    type: 'audio/mp3'
                });

                // Create a new audio element and play the sound
                var audio = new Audio(URL.createObjectURL(blob));
                audio.play();
            });
        });

        document.getElementById('saveItemButton').addEventListener('click', function() {
            var form = document.getElementById('addItemForm');
            var itemName = form.elements['itemName'].value;
            var itemSound = form.elements['itemSound'].files[0];

            if (itemSound.size > 1048576) {
                alert('El archivo de sonido no puede ser mayor a 1MB.');
                return;
            }

            var formData = new FormData();
            formData.append('name', itemName);
            formData.append('sound', itemSound);

            // Get the CSRF token from the meta tag
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/sound', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(function(response) {
                console.log(response);
                if (!response.ok) {
                    throw new Error('Error al guardar el sonido');
                    alert('Error al guardar el sonido');
                }
                return response.json();
            }).then(function(data) {
                console.log('Sonido guardado con éxito:', data);
                alert('Sonido guardado con éxito');
                // Reload the page to show the new item
                window.location.reload();
                //limpiar formulario
                form.reset();
            }).catch(function(error) {
                console.error('Error:', error);
            });
        });

        document.querySelectorAll('.delete-item').forEach(function(button) {
            button.addEventListener('click', function() {
                var itemId = this.getAttribute('data-id');
                console.log(itemId);

                // Get the CSRF token from the meta tag
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                fetch(`/sound/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                }).then(function(response) {
                    console.log(response);
                    if (!response.ok) {
                        throw new Error('Error al eliminar el ítem');
                    }
                    return response.json();
                }).then(function(data) {
                    if (data.success) {
                        // Remove the item from the DOM
                        button.closest('li').remove();
                        alert('Ítem eliminado con éxito');
                    } else {
                        alert('Error al eliminar el ítem');
                    }
                }).catch(function(error) {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</body>

</html>
