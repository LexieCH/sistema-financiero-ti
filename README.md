# Sistema Financiero ERP - Laravel

Sistema financiero multiempresa desarrollado en Laravel para gestión de ingresos, egresos y documentos contables.

Proyecto realizado como práctica profesional.

## Tecnologías
- Laravel 12
- MySQL
- Bootstrap
- Laravel Breeze
- Git/GitHub

## Instalación

Clonar repositorio:

git clone URL_REPO  
cd sistema-financiero

Instalar dependencias:

composer install  
npm install  

Copiar .env:

cp .env.example .env  

Configurar base de datos en .env

php artisan key:generate  

## Base de datos

php artisan migrate:fresh --seed

## Usuario demo

admin@admin.cl  
12345678

## Ejecutar sistema

php artisan serve