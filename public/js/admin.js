document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.ver-arboles');
    const container = document.getElementById('arboles-container');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const amigoId = button.dataset.amigoId;

            // Realizar una solicitud AJAX
            fetch(`<?= base_url('admin/viewFriendTrees'); ?>/${amigoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        container.innerHTML = `<p class="text-danger">${data.error}</p>`;
                    } else {
                        const arboles = data.arboles;
                        const amigo = data.amigo;

                        if (arboles.length > 0) {
                            let html = `
                                <h4>Árboles de ${amigo.nombre}</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Tamaño</th>
                                            <th>Ubicación</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                            arboles.forEach(arbol => {
                                html += `
                                    <tr>
                                        <td>${arbol.nombre}</td>
                                        <td>${arbol.tamano}</td>
                                        <td>${arbol.ubicacion_geografica}</td>
                                        <td>${arbol.estado}</td>
                                        <td>
                                            <a href="<?= base_url('admin/updateTree'); ?>/${arbol.id}" class="btn btn-primary btn-sm">Actualizar</a>
                                            <a href="<?= base_url('admin/deleteTree'); ?>/${arbol.id}" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este árbol?');">Eliminar</a>
                                        </td>
                                    </tr>`;
                            });
                            html += `</tbody></table>`;
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = `<p>No hay árboles registrados para este amigo.</p>`;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = `<p class="text-danger">Error al cargar los árboles.</p>`;
                });
        });
    });
});
