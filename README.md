# Paretomed - Extensiones

**Paretomed - Extensiones** es un plugin de WordPress diseñado para añadir funcionalidades específicas basadas en el principio de Pareto, facilitando la personalización y optimización de sitios web. Este plugin permite gestionar diversas características como la desactivación del clic derecho, limitación de sesiones simultáneas, y personalizaciones para WooCommerce.

## Características

- **Desactivar clic derecho y otros atajos de seguridad**: Protege el contenido de tu sitio web desactivando el clic derecho, la selección de texto, arrastre de elementos, y atajos de teclado.
- **Limitación de sesiones simultáneas**: Evita que un usuario inicie sesión en múltiples dispositivos al mismo tiempo.
- **Personalizaciones para WooCommerce**: Añade funcionalidades específicas como redirecciones personalizadas al finalizar la compra, limitación de productos en el carrito, y mucho más.
- **Redirecciones personalizadas**: Controla las redirecciones después del login, registro, y otras acciones del usuario.
- **Integración con Meta Pixel**: Añade automáticamente el código de seguimiento de Meta Pixel en todas las páginas.

## Instalación

1. **Descargar**: Descarga el archivo ZIP del plugin desde el repositorio.
2. **Subir a WordPress**: Ve a `Plugins > Añadir nuevo > Subir plugin` en el panel de administración de WordPress. Selecciona el archivo ZIP y haz clic en "Instalar ahora".
3. **Activar el plugin**: Una vez instalado, haz clic en "Activar" para empezar a usar Paretomed - Extensiones.

## Configuración

Después de activar el plugin, puedes configurar sus opciones en `Ajustes > Paretomed`. Aquí podrás:

- Habilitar o deshabilitar la protección contra clic derecho.
- Limitar sesiones simultáneas.
- Configurar personalizaciones específicas para WooCommerce.

## Requisitos

- WordPress 5.0 o superior
- PHP 7.2 o superior
- WooCommerce (opcional para las funcionalidades específicas de WooCommerce)

## Uso

### Desactivar Clic Derecho

Puedes activar o desactivar esta opción en la página de configuración del plugin (`Ajustes > Paretomed`). Si está activado, el clic derecho, la selección de texto, y otros atajos de seguridad serán desactivados para proteger tu contenido.

### Limitación de Sesiones Simultáneas

Esta opción evita que los usuarios inicien sesión en múltiples dispositivos simultáneamente, añadiendo una capa extra de seguridad.

### Personalizaciones de WooCommerce

El plugin permite realizar personalizaciones avanzadas como limitar el carrito a un solo producto o redirigir automáticamente a la página de pago cuando se añade un producto específico.

## Desarrollo

### Estructura de Archivos

- `paretomed-extensiones.php`: Archivo principal del plugin.
- `includes/admin-settings.php`: Maneja la configuración del plugin en el panel de administración.
- `includes/class-wp-bouncer.php`: Gestiona la limitación de sesiones simultáneas.
- `includes/woocommerce-customizations.php`: Contiene las personalizaciones para WooCommerce.
- `includes/redirections.php`: Gestiona las redirecciones personalizadas en el sitio web.
- `js/disable-right-click.js`: Script para desactivar el clic derecho y otros atajos de seguridad.
