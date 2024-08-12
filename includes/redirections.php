<?php

function redirect_specific_pages() {
    // Obtener el URI solicitado sin la barra inicial
    $requested_uri = trim($_SERVER['REQUEST_URI'], '/');

    // Redirige desde "cursos" a "/lp-perfil/#modulos"
    if ($requested_uri === 'cursos') {
        wp_redirect(home_url('/lp-perfil/#modulos'));
        exit;
    }

    // Redirige desde "modulos" a "/lp-perfil/#modulos"
    if ($requested_uri === 'modulos') {
        wp_redirect(home_url('/lp-perfil/#modulos'));
        exit;
    }
    if ($requested_uri === 'niveles-de-membresia') {
        $redirect_url = add_query_arg('showToast', '1', home_url('/precio/'));
        wp_redirect($redirect_url);
        exit;
    }
    if ($requested_uri === 'acceder' && isset($_SERVER['HTTP_REFERER'])) {
        $referrer = $_SERVER['HTTP_REFERER'];
        // Comprueba si el referrer es el especificado
        if ($referrer === 'https://paretomed.org/wp-login.php?loginSocial=google') {
            wp_redirect(home_url('/lp-perfil/'));
            exit;
        }
    }
}
add_action('template_redirect', 'redirect_specific_pages', 1); // Ejecuta antes, prioridad más alta

// Redirigir una URL específica
function redireccionar_url() {
    if (is_page('registro-de-estudiante-2')) {
        wp_redirect(home_url('/registro-alt/'), 301);
        exit;
    }
}
add_action('template_redirect', 'redireccionar_url');

// Verificar login y redirigir si es necesario
function add_login_check() {
    if (is_user_logged_in() && is_page(5819)) {
        wp_redirect(home_url('/lp-perfil'));
        exit;
    }
}
add_action('wp', 'add_login_check');

// Personalizar redirección después del login
function my_login_redirect($redirect_to, $request, $user) {
    // Puedes agregar lógica adicional aquí, por ejemplo, basada en roles de usuario o condiciones específicas
    return home_url('/lp-perfil/'); // Redirige a los usuarios después de iniciar sesión a una página específica
}
// Asegúrate de pasar 3 argumentos a la función y establecer la prioridad a 999
add_filter('login_redirect', 'my_login_redirect', 999, 3);

// Redirigir después del registro
function my_registration_redirect() {
    return home_url('/r-gracias/');
}
add_filter('registration_redirect', 'my_registration_redirect');

// Redirección personalizada en base a condiciones específicas
function custom_redirection() {
    if (strpos($_SERVER['REQUEST_URI'], '/finalizar-compra/?level=2') !== false) {
        wp_redirect('https://paretomed.org/registro-de-estudiante-2/');
        exit;
    }
}
add_action('template_redirect', 'custom_redirection');
