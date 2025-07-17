<!DOCTYPE html>
<html>
<head>
    <title>Mi Panel</title>
    <!-- Añadir meta charset para caracteres especiales -->
    <meta charset="UTF-8">
    <!-- Añadir viewport para responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- Verificar primero si la sesión y el email existen -->
    <h1>Bienvenido <?= isset($_SESSION['user']['email']) ? htmlspecialchars($_SESSION['user']['email']) : 'Usuario' ?></h1>
    
    <h2>Tus libros:</h2>
    
    <!-- Verificar si hay libros antes de mostrar la lista -->
    <?php if (!empty($books)): ?>
        <ul>
            <?php foreach ($books as $book): ?>
                <!-- Corrección: quitar el named parameter (string:) que no es necesario -->
                <li><?= htmlspecialchars($book['title']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tienes libros registrados.</p>
    <?php endif; ?>
    
    <!-- Mejorar el enlace de logout con estilo básico -->
    <div style="margin-top: 20px;">
        <a href="/logout" style="color: #fff; background: #d9534f; padding: 8px 12px; text-decoration: none; border-radius: 4px;">Cerrar sesión</a>
    </div>

    <!-- Añadir algo de CSS básico -->
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 8px;
            background: #f4f4f4;
            margin: 5px 0;
            border-left: 4px solid #5bc0de;
        }
    </style>
</body>
</html>