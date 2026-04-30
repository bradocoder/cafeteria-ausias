# Cafetería CIPFP Àusias March — Capa Web

Sistema de pedidos online para la cafetería del instituto.
Parte correspondiente a frontend + servidores web (WEB1 / WEB2).

Proyecto intermodular de 2º ASIR (curso 2025-2026).

## Arquitectura general
Cliente → pfSense (firewall) → Balanceador NGINX (DMZ)
↓
WEB1 / WEB2 (DMZ, este repo)
↓
MariaDB en LAN (172.16.2.x)

## Stack

- **Servidor web:** NGINX 1.24+
- **Lenguaje:** PHP 8.3 con FPM
- **Base de datos:** MariaDB 10.11 (gestionada por otro miembro del equipo)
- **SO:** Ubuntu Server 24.04 LTS en contenedor LXC sobre Proxmox

## Estructura del proyecto
cafeteria/
├── public/                  # Único docroot expuesto
│   ├── index.php            # Front controller
│   └── assets/              # CSS, JS, imágenes
├── src/
│   ├── Controllers/         # Lógica por ruta
│   ├── Models/              # Acceso a datos (PDO)
│   ├── Services/            # Lógica de negocio (futuro)
│   └── Helpers/             # Utilidades (futuro)
├── templates/               # Vistas HTML
│   └── layout/              # Header / footer comunes
├── storage/                 # Logs, caché (no versionado)
└── config.example.php       # Plantilla de configuración

## Despliegue local (en un nuevo LXC)

```bash
# 1. Instalar dependencias
apt update
apt install -y nginx php8.3-fpm php8.3-mysql git

# 2. Clonar repo
cd /var/www
git clone https://github.com/USUARIO/cafeteria-web.git cafeteria

# 3. Crear configuración real (FUERA del repo)
mkdir -p /etc/cafeteria
cp /var/www/cafeteria/config.example.php /etc/cafeteria/config.php
chown root:www-data /etc/cafeteria/config.php
chmod 640 /etc/cafeteria/config.php
# ✏️ Editar /etc/cafeteria/config.php con credenciales reales

# 4. Permisos
chown -R www-data:www-data /var/www/cafeteria
chmod -R 775 /var/www/cafeteria/storage

# 5. NGINX (server block en /etc/nginx/sites-available/cafeteria)
# Ver documentación en /docs/nginx.md (pendiente)

# 6. Reiniciar
systemctl reload nginx
```

## Convenciones de código

- PHP: `declare(strict_types=1)` en todos los archivos
- Consultas SQL: SIEMPRE con sentencias preparadas (PDO con `prepare`/`execute`)
- HTML escapado con `htmlspecialchars()` antes de imprimir variables
- Sin lógica de negocio en templates: solo presentación

## Equipo

| Rol | Responsable |
|---|---|
| Frontend + Servidores web | Luis (este repo) |
| Base de datos | Carlota |
| Backend + Seguridad | Edgar |
| Balanceador de carga | Sergio |

## Estado actual del desarrollo

- [x] Infraestructura WEB1 con NGINX + PHP-FPM
- [x] Conexión a MariaDB en LAN
- [x] Listado de productos con filtros por categoría
- [x] Detalle de producto
- [x] Carrito en localStorage
- [ ] Sistema de login (en desarrollo)
- [ ] Creación de pedidos en BD
- [ ] Panel admin
- [ ] Despliegue en WEB2

## Licencia

Proyecto académico. Uso interno del CIPFP Àusias March.
