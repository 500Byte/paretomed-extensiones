<?php
// Evitar que más de un producto esté en el carrito a la vez
add_filter('woocommerce_add_to_cart_validation', 'only_one_in_cart', 9999, 2);
function only_one_in_cart($passed, $added_product_id) {
    wc_empty_cart();
    return $passed;
}

// Redirigir directamente al checkout después de añadir al carrito
add_filter('add_to_cart_redirect', 'skip_cart_page');
function skip_cart_page() {
    return wc_get_checkout_url();
}

// Comprar membresía premium automáticamente al visitar finalizar-compra
add_action('init', 'woo_comprar_premium');
function woo_comprar_premium() {
    // Comprar automáticamente al visitar una URL específica para finalizar la compra con level=1
    if (strpos($_SERVER['REQUEST_URI'], '/finalizar-compra/?level=1') !== false) {
        wp_redirect('/finalizar-compra/?add-to-cart=1232');
        exit;
    }

    // Comprar automáticamente y redirigir basado en el course_id específico
    if (strpos($_SERVER['REQUEST_URI'], '/niveles-de-membresia/') !== false && isset($_GET['course_id'])) {
        $course_id = $_GET['course_id'];
        switch ($course_id) {
            case '5450':
                // Suponiendo que el ID 1232 del producto corresponde a la membresía para el course_id 5450
                wp_redirect('/finalizar-compra/?add-to-cart=1232');
                break;
            case '4645':
                // Redirigir a una página de registro alternativa para el course_id 4645
                wp_redirect('https://paretomed.org/registro-alt/');
                break;
            default:
                // Puedes manejar otros course_id o dejar sin acción
                break;
        }
        exit;
    }
}


// Añadir una nueva pasarela de pago para productos gratuitos
add_filter('woocommerce_payment_gateways', 'add_mi_pasarela_gratuita');
function add_mi_pasarela_gratuita($gateways) {
    $gateways[] = 'WC_Gateway_Mi_Pasarela_Gratuita';
    return $gateways;
}

// Definir la clase de la pasarela de pago
add_action('plugins_loaded', 'init_mi_pasarela_gratuita');
function init_mi_pasarela_gratuita() {
    class WC_Gateway_Mi_Pasarela_Gratuita extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'mi_pasarela_gratuita';
            $this->icon = apply_filters('woocommerce_custom_gateway_icon', plugins_url('images/icono_precio.png', __FILE__));
            $this->has_fields = false;
            $this->method_title = 'Gratuito';
            $this->method_description = 'Pasarela para productos gratuitos';

            // Soporte solo para pedidos gratuitos
            $this->enabled = isset(WC()->cart) && WC()->cart->total > 0 ? 'no' : 'yes';

            // Cargar los ajustes
            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('title');
            
            // Guardar ajustes
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'       => 'Activar/Desactivar',
                    'label'       => 'Activar Pasarela Gratuita',
                    'type'        => 'checkbox',
                    'default'     => 'yes'
                ),
                'title' => array(
                    'title'       => 'Título',
                    'type'        => 'text',
                    'default'     => 'Gratuito',
                    'desc_tip'    => true,
                ),
            );
        }

        public function process_payment($order_id) {
            $order = wc_get_order($order_id);

            // Marcar como completado
            $order->payment_complete();

            // Reducción del inventario
            wc_reduce_stock_levels($order_id);

            // Retorno
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url($order),
            );
        }
    }
}
