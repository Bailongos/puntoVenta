# Manual de Instalación — Punto de Venta

## Requisitos

| Requisito | Versión |
|-----------|---------|
| XAMPP (PHP + MySQL) | 8.0 o superior |
| Navegador web | Chrome, Firefox, Edge |

---

## Paso 1 — Instalar XAMPP

1. Descargar XAMPP desde <https://www.apachefriends.org/>
2. Ejecutar el instalador y aceptar los valores por defecto
3. Al finalizar, abrir **XAMPP Control Panel**
4. Dar clic en **Start** en los módulos **Apache** y **MySQL**
5. Verificar que ambos aparezcan en verde

---

## Paso 2 — Copiar el proyecto

1. Copiar la carpeta `punto-de-venta-ruben` completa
2. Pegarla en `C:\xampp\htdocs\`

La ruta final debe quedar así:

```
C:\xampp\htdocs\punto-de-venta-ruben\
```

---

## Paso 3 — Ejecutar la migración

1. Abrir el navegador
2. Ir a esta dirección:

```
http://localhost/punto-de-venta-ruben/migracion.php
```

3. Esperar a que aparezca el mensaje **"Resumen: 4 roles · 10 permisos · 2 usuarios (1 admin)"**
4. Si todo salió bien, aparecerá un enlace **"← Ir al login"**

> **Nota:** la migración crea automáticamente la base de datos, las tablas, los roles, los permisos y el usuario administrador. No requiere phpMyAdmin ni configuración manual.

---

## Paso 4 — Iniciar sesión

1. Ir a:

```
http://localhost/punto-de-venta-ruben/auth/login.php
```

2. Ingresar las credenciales:

| Campo | Valor |
|-------|-------|
| Usuario | `admin` |
| Contraseña | `admin` |

3. Dar clic en **Iniciar Sesión**

---

## Primeros pasos

Una vez dentro del panel:

1. **Registrar usuarios** — Ir a Usuarios → Usuarios y crear usuarios con diferentes roles (Cajero, Gerente, Almacén)
2. **Dar de alta productos** — Ir a Artículos y registrar productos con código de barras, precios y stock
3. **Registrar clientes** — Ir a Clientes y agregar clientes frecuentes
4. **Ajustar inventario** — Ir a Inventarios para registrar entradas y salidas de stock
5. **Vender** — Ir a Punto de Venta, seleccionar productos y cobrar
6. **Revisar ventas** — Ir a Reportes para ver el historial de transacciones

---

## Estructura de carpetas

```
punto-de-venta-ruben/
├── index.php              → Panel de inicio (dashboard)
├── migracion.php          → Instalación de base de datos
├── INSTALAR.md            → Este manual
│
├── config/
│   ├── conexion.php       → Configuración de base de datos
│   └── funciones.php      → Helper de permisos
│
├── includes/
│   ├── dashboard-header.php  → Header del panel
│   ├── dashboard-footer.php  → Footer del panel + modal ticket
│   └── navbar.php         → Menú lateral (sidebar)
│
├── assets/
│   ├── css/
│   │   ├── menu.css       → Estilos del panel
│   │   ├── login.css      → Estilos de login
│   │   └── style.css      → Estilos legacy
│   └── js/
│       └── main.js        → JavaScript global
│
├── auth/
│   ├── login.php          → Inicio de sesión
│   ├── register.php       → Registro público
│   ├── recuperar.php      → Recuperación de contraseña
│   └── cerrar_sesion.php  → Cerrar sesión
│
├── api/
│   ├── ver_ticket.php     → AJAX: detalle de ticket
│   └── exportar.php       → Exportar Excel/PDF
│
└── modules/
    ├── articulos/
    │   └── articulos.php  → Módulo de artículos
    ├── clientes/
    │   └── clientes.php   → Módulo de clientes
    ├── corte_caja/
    │   └── corte_caja.php → Corte de caja
    ├── inventarios/
    │   └── inventarios.php → Módulo de inventarios
    ├── pos/
    │   └── pos.php        → Punto de venta (POS)
    ├── reportes/
    │   └── reportes.php   → Reportes
    └── usuarios/
        ├── usuarios.php   → Gestión de usuarios
        ├── roles.php      → Gestión de roles
        ├── permisos.php   → Matriz de permisos
        └── usuarios.css   → Estilos del módulo
```

---

## Roles del sistema

| Rol | Acceso |
|-----|--------|
| **Administrador** | Todo el sistema (usuarios, roles, permisos, reportes, inventario, POS) |
| **Gerente** | Inicio, POS, clientes, artículos, inventario, reportes, corte de caja |
| **Cajero** | Inicio, POS, clientes, corte de caja |
| **Almacén** | Inicio, artículos, inventario |

---

## Solución de problemas

### "Error de conexión"
- Verificar que Apache y MySQL estén **Start** en XAMPP Control Panel
- Verificar que MySQL no tenga otro programa usando el puerto 3306

### "Base de datos no encontrada"
- Ejecutar nuevamente `http://localhost/punto-de-venta-ruben/migracion.php`
- Si el error persiste, abrir phpMyAdmin y ejecutar:
  ```sql
  CREATE DATABASE punto_de_venta DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```
  Luego volver a ejecutar migracion.php

### "Página en blanco" al guardar
- Verificar que la URL en el navegador comience con `http://localhost/...`
- No usar `https://`

### No aparece el menú lateral
- Cerrar sesión y volver a iniciar sesión
- Si el usuario se creó antes de la migración, necesita re-iniciar sesión para cargar los permisos

---

## Créditos

Sistema de Punto de Venta desarrollado en PHP con MySQL.
