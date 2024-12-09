<?php

function verificarYEnviarCorreoSiAdmin($esAdmin) {
    if (!$esAdmin) {
        return; // Salir si el usuario no es admin
    }

    // Configuración del correo
    $admin_email = "proyectoka123@gmail.com";
    $subject = "Recordatorio: Actualización de árboles desactualizados";
    
    // Conectar a la base de datos usando la función getConnection
    $conn = getConnection();
    
    // Calcular la fecha límite (1 mes atrás)
    $fecha_limite = date("Y-m-d H:i:s", strtotime("-1 month"));
    
    // Consultar árboles desactualizados considerando solo la última fecha de actualización
    $sql = "
        SELECT a.id, a.ubicacion_geografica 
        FROM arboles a
        LEFT JOIN (
            SELECT arbol_id, MAX(fecha_actualizacion) AS ultima_actualizacion
            FROM actualizaciones
            GROUP BY arbol_id
        ) act ON a.id = act.arbol_id
        WHERE act.ultima_actualizacion < '$fecha_limite' OR act.ultima_actualizacion IS NULL
    ";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Crear el contenido del correo en formato HTML
        $lista_arboles = "
            <h2>Los siguientes árboles no han sido actualizados desde hace 1 mes:</h2>
            <table style='width:100%; border-collapse: collapse;'>
                <thead>
                    <tr>
                        <th style='border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;'>ID del Árbol</th>
                        <th style='border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;'>Ubicación</th>
                    </tr>
                </thead>
                <tbody>
        ";

        while ($row = mysqli_fetch_assoc($result)) {
            $lista_arboles .= "
                <tr>
                    <td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>{$row['id']}</td>
                    <td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>{$row['ubicacion_geografica']}</td>
                </tr>
            ";
        }

        $lista_arboles .= "
                </tbody>
            </table>
            <p style='font-size: 14px; color: #555;'>Por favor, actualice la información de los árboles desactualizados.</p>
        ";
        
        // Configuración del correo en formato HTML
        $headers = "From: angiehh1724@gmail.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        // Enviar el correo
        if (mail($admin_email, $subject, $lista_arboles, $headers)) {
            echo "Correo enviado al administrador.";
        } else {
            echo "Error al enviar el correo.";
        }
    } else {
        echo "No hay árboles desactualizados.";
    }
    
    // Cerrar conexión
    mysqli_close($conn);
}

function perteneceAlUsuario($usuario_id, $arbol_id) {
    $conn = getConnection();
    $sql = "SELECT * FROM arboles a 
            JOIN amigo_arbol aa ON a.id = aa.arbol_id 
            WHERE aa.amigo_id = ? AND a.id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $arbol_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $existe = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $existe;
}

