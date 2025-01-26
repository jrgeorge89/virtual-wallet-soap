# Proyecto Billetera Virtual - Servicios SOAP (Laravel)

Bienvenido al Proyecto Billetera Virtual implementando servicios SOAP. Este proyecto utiliza el framework Laravel, a continuación encontrarás información importante sobre cómo clonar el repositorio, instalar las dependencias.

## 🚀 Clonar el Repositorio

Para clonar este repositorio en tu máquina local, sigue estos pasos:

1. **Clona el repositorio usando Git:**

    ```bash
    git clone https://github.com/jrgeorge89/virtual-wallet-soap.git
    ```

2. **Navega a la carpeta del proyecto:**

    ```bash
    cd tu-repositorio
    ```

## 🔧 Instalación y Ejecución

Para instalar las dependencias y ejecutar el proyecto, sigue estos pasos:

1. **Instala la dependencia del proyecto:**

    Asegúrate de tener [Composer](https://getcomposer.org/) instalado.

    ```bash
    composer install
    ```

2. **Configura el archivo de entorno:**

    Copia el archivo `.env.example` a `.env` y configura las variables de entorno según tu entorno local.

    ```bash
    cp .env.example .env
    ```

3. **Genera la clave de aplicación de Laravel:**

    ```bash
    php artisan key:generate
    ```

4. **Ejecuta las migraciones (BD MySQL):**

    ```bash
    php artisan migrate
    ```

5 **Generar Clases de Proxy para el ORM Dotrine:**

    ```bash
    php artisan doctrine:generate:proxies
    ```

6. **Inicia el servidor de desarrollo:**

    ```bash
    php artisan serve
    ```

   El proyecto estará disponible en [http://127.0.0.1:8000](http://127.0.0.1:8000).

   ! Una vez inicializado el proyecto, puedes probar el proyecto por medio de Postman con los diferentes Endpoint tanto para los servicios Rest como SOAP ¡.

## ✨ Funcionalidades Implementadas

Este proyecto incluye la siguiente funcionalidades:

- Registro de clientes.
- Recarga de billeteras.
- Pagos y confirmación de pagos.
- Consulta de saldos.

**¡Listo! Ahora estás listo para comenzar a revisar el Proyecto. ¡Gracias!** 😊
