<?php
/*
Plugin Name: Paretomed - Extensiones
Plugin URI: http://paretomed.org
Description: Un plugin para añadir funcionalidades específicas según el principio de Pareto.
Version: 1.0
Author URI: http://paretomed.org
*/

// Incluir archivos de funciones y clases
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wp-bouncer.php';
require_once plugin_dir_path(__FILE__) . 'includes/woocommerce-customizations.php';
require_once plugin_dir_path(__FILE__) . 'includes/redirections.php';

// Hook en el menú de administración para agregar la página de opciones
add_action('admin_menu', 'paretomed_menu');

// Hook para registrar ajustes
add_action('admin_init', 'paretomed_register_settings');

// Hook para scripts específicos
function paretomed_enqueue_scripts() {
    if (!is_admin()) {
        if (get_option("paretomed_disable_right_click") == 1) {
            wp_enqueue_script(
                "paretomed-disable-right-click",
                plugin_dir_url(__FILE__) . "js/disable-right-click.js",
                array(),
                null,
                true
            );
        }
    }
    echo '<script>var is_admin = ' . (is_admin() ? 'true' : 'false') . ';</script>';
}
add_action('wp_enqueue_scripts', 'paretomed_enqueue_scripts');
add_action('admin_enqueue_scripts', 'paretomed_enqueue_scripts');

// Hook para instanciar y gestionar las funcionalidades del Bouncer
$WP_Bouncer = new WP_Bouncer();

// Hook para acciones específicas de WooCommerce
add_action('init', 'woo_comprar_premium');
add_filter('add_to_cart_redirect', 'skip_cart_page');
add_filter('woocommerce_add_to_cart_validation', 'only_one_in_cart', 9999, 2);

// Hook para redirecciones personalizadas
add_action('template_redirect', 'redirect_specific_pages');
add_action('template_redirect', 'redireccionar_url');
add_action('wp', 'add_login_check');

// Hooks para personalizar las redirecciones de login y registro
add_filter('login_redirect', 'my_login_redirect', 999, 3);
add_filter('registration_redirect', 'my_registration_redirect');
add_action('template_redirect', 'custom_redirection');

// Añadir la nueva pasarela de pago y definir la clase de la pasarela de pago
add_filter('woocommerce_payment_gateways', 'add_mi_pasarela_gratuita');
add_action('plugins_loaded', 'init_mi_pasarela_gratuita');

// Disables the pmpro redirect to levels page when user tries to register
add_filter( 'pmpro_login_redirect', '__return_false' );

function my_pmpro_default_registration_level( $user_id ) {
	pmpro_changeMembershipLevel( 2, $user_id );
}
add_action( 'user_register', 'my_pmpro_default_registration_level' );

function change_text_except_on_specific_page() {
    if (!is_page(4645)) {
        ?>
        <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Reemplazar el texto del botón
            var retakeButton = document.querySelector(".lp-button.button.button-retake-course");
            if (retakeButton) {
                retakeButton.textContent = "Retomar curso";
            }

            // Reemplazar el texto del span dentro de .course-price
            var span = document.querySelector('.course-price > span > span');
            if (span) {
                span.textContent = 'Premium';
            }
        });

        </script>
        <?php
    }
}
add_action('wp_head', 'change_text_except_on_specific_page');

// Hook para añadir el Meta Pixel Code
function add_meta_pixel_code() {
    ?>
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '838228641486618');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=838228641486618&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    <?php
}
add_action('wp_head', 'add_meta_pixel_code');
?>
