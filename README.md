# Sistema Financiero TI

Sistema financiero multiempresa hecho con Laravel.

## Requisitos
- PHP 8.2+
- Composer00000
- Node.js + npm
- MySQL

## Inicio rápido (modo demo)

1. Clonar proyecto

```bash
git clone <URL_DEL_REPO>
cd sistema-financiero-ti
```

2. Instalar dependencias

```bash
composer install
npm install
```

3. Crear archivo de entorno

```bash
cp .env.example .env
```

En Windows PowerShell también puedes usar:

```powershell
copy .env.example .env
```

4. Configurar base de datos en `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_financiero_ti
DB_USERNAME=root
DB_PASSWORD=
```

5. Generar clave y cargar demo

```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

6. Levantar sistema

```bash
php artisan serve
```

## Usuario demo
- Correo: `admin@sistema.cl`
- Clave: `123456`

## Reiniciar demo (limpiar todo)

```bash
php artisan migrate:fresh --seed
```

## Módulos principales
- Empresas
- Usuarios y permisos
- Terceros
- Movimientos
- Documentos
- Pagos
- Centros de costos
- Proyectos
- Bitácora

## Comandos útiles

```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
```

## Problemas comunes

### `php` no se reconoce en PowerShell
Usa la terminal de Laragon o ejecuta con la ruta completa de `php.exe`.

### Error 419 (Page Expired)
- Abrir siempre con el mismo dominio (`localhost` o `127.0.0.1`, no mezclar)
- Limpiar caché de Laravel con los comandos de arriba
- Borrar cookies del sitio y volver a iniciar sesión