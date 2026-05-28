<?php
require_once __DIR__ . '/conexion.php';

$mensajes = [];
$error = false;

// ── 1. Crear tabla roles ──
$conn->query("CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
$mensajes[] = '✓ Tabla roles lista';

$roles_seed = [
  [1, 'Administrador', 'Acceso completo al sistema'],
  [2, 'Gerente', 'Reportes, inventario y clientes'],
  [3, 'Cajero', 'Punto de venta y clientes'],
  [4, 'Almacén', 'Artículos e inventario'],
];

$stmt_rol = $conn->prepare("INSERT IGNORE INTO roles (id, nombre, descripcion) VALUES (?, ?, ?)");
foreach ($roles_seed as $r) {
  $stmt_rol->bind_param('iss', $r[0], $r[1], $r[2]);
  $stmt_rol->execute();
}
$mensajes[] = '✓ Roles insertados';

// ── 2. Crear tabla permisos ──
$conn->query("CREATE TABLE IF NOT EXISTS permisos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
$mensajes[] = '✓ Tabla permisos lista';

$permisos_seed = [
  [1, 'ver_inicio', 'Ver página de inicio'],
  [2, 'ver_punto_venta', 'Acceder al módulo de punto de venta'],
  [3, 'ver_clientes', 'Ver y gestionar clientes'],
  [4, 'ver_articulos', 'Ver y gestionar artículos'],
  [5, 'ver_inventario', 'Ver y gestionar inventario'],
  [6, 'ver_reportes', 'Ver reportes del sistema'],
  [7, 'ver_usuarios', 'Gestionar usuarios del sistema'],
  [8, 'ver_roles', 'Gestionar roles del sistema'],
  [9, 'ver_permisos', 'Gestionar permisos del sistema'],
  [10, 'ver_corte_caja', 'Acceder al módulo de corte de caja'],
];

$stmt_perm = $conn->prepare("INSERT IGNORE INTO permisos (id, nombre, descripcion) VALUES (?, ?, ?)");
foreach ($permisos_seed as $p) {
  $stmt_perm->bind_param('iss', $p[0], $p[1], $p[2]);
  $stmt_perm->execute();
}
$mensajes[] = '✓ Permisos insertados';

// ── 3. Crear tabla rol_permisos ──
$conn->query("CREATE TABLE IF NOT EXISTS rol_permisos (
  rol_id INT NOT NULL,
  permiso_id INT NOT NULL,
  PRIMARY KEY (rol_id, permiso_id),
  FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
$mensajes[] = '✓ Tabla rol_permisos lista';

// Admin: todos (1-10)
$admin_perms = range(1, 10);
$gerente_perms = [1, 2, 3, 4, 5, 6, 10];
$cajero_perms = [1, 2, 3, 10];
$almacen_perms = [1, 4, 5];

$map = [1 => $admin_perms, 2 => $gerente_perms, 3 => $cajero_perms, 4 => $almacen_perms];

$stmt_rp = $conn->prepare("INSERT IGNORE INTO rol_permisos (rol_id, permiso_id) VALUES (?, ?)");
foreach ($map as $rol_id => $permisos) {
  foreach ($permisos as $perm_id) {
    $stmt_rp->bind_param('ii', $rol_id, $perm_id);
    $stmt_rp->execute();
  }
}
$mensajes[] = '✓ Permisos asignados a roles';

// ── 4. Tabla usuarios (crear si no existe) ──
$usuarios_existia = $conn->query("SHOW TABLES LIKE 'usuarios'")->num_rows > 0;

if (!$usuarios_existia) {
  $conn->query("CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    nombre_completo VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    pregunta_segura VARCHAR(255) DEFAULT NULL,
    respuesta_segura VARCHAR(255) DEFAULT NULL,
    rol_id INT NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
  $mensajes[] = '✓ Tabla usuarios creada';

  $admin_pass = password_hash('admin', PASSWORD_DEFAULT);
  $admin_pregunta = '¿Cuál es tu color favorito?';
  $admin_respuesta = password_hash('admin', PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO usuarios (usuario, nombre_completo, password, email, rol_id, pregunta_segura, respuesta_segura)
    VALUES (?, ?, ?, ?, 1, ?, ?)");
  $usr = 'admin';
  $nom = 'Administrador General';
  $mail = 'admin@sistema.local';
  $stmt->bind_param('ssssss', $usr, $nom, $admin_pass, $mail, $admin_pregunta, $admin_respuesta);
  $stmt->execute();
  $stmt->close();
  $mensajes[] = '✓ Usuario admin creado (usuario: admin / contraseña: admin)';
} else {
  $mensajes[] = '→ Tabla usuarios ya existe, verificando columnas...';

  // Agregar columnas faltantes (para migración desde versión anterior)
  $cols = [];
  $r = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'email'");
  if ($r->num_rows === 0) $cols[] = "ADD COLUMN email VARCHAR(100) DEFAULT NULL AFTER password";

  $r = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'telefono'");
  if ($r->num_rows === 0) $cols[] = "ADD COLUMN telefono VARCHAR(20) DEFAULT NULL AFTER email";

  $r = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'pregunta_segura'");
  if ($r->num_rows === 0) $cols[] = "ADD COLUMN pregunta_segura VARCHAR(255) DEFAULT NULL AFTER telefono";

  $r = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'respuesta_segura'");
  if ($r->num_rows === 0) $cols[] = "ADD COLUMN respuesta_segura VARCHAR(255) DEFAULT NULL AFTER pregunta_segura";

  $r = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'rol_id'");
  $rol_exists = ($r->num_rows > 0);

  if (!$rol_exists) {
    $cols[] = "ADD COLUMN rol_id INT DEFAULT NULL AFTER respuesta_segura";
  }

  if ($cols) {
    $sql = "ALTER TABLE usuarios " . implode(', ', $cols);
    if ($conn->query($sql)) {
      $mensajes[] = '✓ Columnas nuevas agregadas a usuarios';
    } else {
      $mensajes[] = '✗ Error al agregar columnas: ' . $conn->error;
      $error = true;
    }
  }

  // Migrar valores de rol → rol_id (solo si la columna vieja aún existe y no se ha migrado)
  if (!$rol_exists) {
    $check_col = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'rol'");
    if ($check_col->num_rows > 0) {
      $conn->query("UPDATE usuarios SET rol_id = 1 WHERE rol = 'admin'");
      $conn->query("UPDATE usuarios SET rol_id = 3 WHERE rol = 'cajero'");
      $conn->query("UPDATE usuarios SET email = CONCAT(usuario, '@sistema.local') WHERE email IS NULL AND rol_id IS NOT NULL");
      $mensajes[] = '✓ Valores de rol migrados a rol_id';

      $conn->query("ALTER TABLE usuarios DROP COLUMN rol");
      $conn->query("ALTER TABLE usuarios MODIFY COLUMN rol_id INT NOT NULL");
      $conn->query("ALTER TABLE usuarios ADD FOREIGN KEY (rol_id) REFERENCES roles(id)");
      $mensajes[] = '✓ Columna rol eliminada, FK rol_id agregada';
    } else {
      $conn->query("ALTER TABLE usuarios MODIFY COLUMN rol_id INT NOT NULL");
      $conn->query("ALTER TABLE usuarios ADD FOREIGN KEY (rol_id) REFERENCES roles(id)");
      $mensajes[] = '✓ FK rol_id agregada a usuarios';
    }
  } else {
    $mensajes[] = '→ Migración de usuarios ya aplicada previamente';
  }
}

// Asegurar que admin existe (si no hay ningún usuario admin)
$check_admin = $conn->query("SELECT COUNT(*) AS c FROM usuarios WHERE usuario = 'admin'")->fetch_assoc()['c'];
if ($check_admin == 0) {
  $admin_pass = password_hash('admin', PASSWORD_DEFAULT);
  $admin_pregunta = '¿Cuál es tu color favorito?';
  $admin_respuesta = password_hash('admin', PASSWORD_DEFAULT);
  $stmt = $conn->prepare("INSERT INTO usuarios (usuario, nombre_completo, password, email, rol_id, pregunta_segura, respuesta_segura)
    VALUES (?, ?, ?, ?, 1, ?, ?)");
  $usr = 'admin';
  $nom = 'Administrador General';
  $mail = 'admin@sistema.local';
  $stmt->bind_param('ssssss', $usr, $nom, $admin_pass, $mail, $admin_pregunta, $admin_respuesta);
  $stmt->execute();
  $stmt->close();
  $mensajes[] = '✓ Usuario admin creado (usuario: admin / contraseña: admin)';
}

// ── Verificar ──
$check_roles = $conn->query("SELECT COUNT(*) AS c FROM roles")->fetch_assoc()['c'];
$check_permisos = $conn->query("SELECT COUNT(*) AS c FROM permisos")->fetch_assoc()['c'];
$check_usuarios = $conn->query("SELECT COUNT(*) AS c FROM usuarios")->fetch_assoc()['c'];
$check_admins = $conn->query("SELECT COUNT(*) AS c FROM usuarios WHERE usuario = 'admin'")->fetch_assoc()['c'];

$mensajes[] = "<br><strong>Resumen:</strong> $check_roles roles · $check_permisos permisos · $check_usuarios usuarios ($check_admins admin)";
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Migración - Punto de Venta</title>
  <style>
    body { font-family: 'Inter', sans-serif; background: #163830; color: #e2e8f0; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
    .card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; padding: 40px; max-width: 540px; width: 90%; box-shadow: 0 24px 60px rgba(0,0,0,0.3); }
    h1 { margin: 0 0 8px; font-size: 28px; }
    p { color: #94a3b8; font-size: 14px; margin: 0 0 24px; }
    .msg { padding: 10px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 6px; }
    .msg.ok { background: rgba(34,197,94,0.15); color: #86efac; }
    .msg.err { background: rgba(239,68,68,0.15); color: #fca5a5; }
    a { display: inline-block; margin-top: 20px; color: #7dc6c7; text-decoration: none; font-weight: 600; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Migración</h1>
    <p>Actualización de base de datos para roles y permisos</p>
    <?php foreach ($mensajes as $m): ?>
      <div class="msg <?php echo strpos($m, '✗') === 0 ? 'err' : (strpos($m, '✓') === 0 ? 'ok' : ''); ?>"><?php echo $m; ?></div>
    <?php endforeach; ?>
    <a href="login.php">← Ir al login</a>
  </div>
</body>
</html>
