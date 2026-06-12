# SoundWave Store 🎶

E-commerce de instrumentos musicales desarrollado con Laravel.  
Permite a los usuarios explorar, agregar al carrito y comprar instrumentos musicales de manera sencilla y segura.

---

## 🚀 Características principales
- Catálogo de instrumentos musicales con fotos, precios, stock y características.
- Carrito de compras persistente.
- Órdenes y pagos (Por el momento sin integraciones).
- Panel de administración para gestionar productos, usuarios, pedidos y consultas.
- Autenticación y perfiles de usuario.

---

## 📂 Estructura del proyecto

- **app/** → Lógica principal de la aplicación (Controllers, Models, Policies, Providers).
- **bootstrap/** → Configuración inicial de Laravel.
- **config/** → Archivos de configuración del sistema.
- **database/** → Migraciones y seeders.
- **lang/** → Archivos de traducción.
- **public/** → Archivos accesibles públicamente (CSS, JS compilado, imágenes).
- **resources/** → Vistas Blade, archivos CSS y JS fuente.
- **routes/** → Definición de rutas (`web.php`, `console.php`).
- **storage/** → Logs, caché y archivos generados.
- **tests/** → Pruebas unitarias y funcionales.
- **vendor/** → Dependencias instaladas vía Composer.

---

## 🛠️ Instalación
1. Clonar el repositorio:
   ```bash
   git clone https://github.com/tuusuario/soundwave-store.git
   cd soundwave-store
   ```

2. Configurar variables de entorno en `.env` (Copias el `.env.example` y agregas tus datos).

3. Instalar Dependencias y generamos clave
    ```bash
    composer install
    npm install
    php artisan key:generate
    ```

4. Ejecutamos migración
```bash
    php artisan migrate:fresh --seed 
```

5. Iniciamos servidor