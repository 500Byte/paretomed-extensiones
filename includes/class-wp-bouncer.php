<?php
class WP_Bouncer {
    /**
     * Constructor de la clase.
     */
    public function __construct() {
        add_action("plugins_loaded", [$this, "textdomain"]);
        add_action("wp_login", [$this, "login_track"]);
        add_action("init", [$this, "login_flag"], 10);
        add_filter("user_row_actions", [$this, "user_row_actions"], 10, 2);
        add_action("admin_init", [$this, "reset_session"]);
        add_action("admin_notices", [$this, "admin_notices"]);
        add_action("wp_ajax_wp_bouncer_check", [$this, "ajax_check"]);
        add_action("wp_ajax_nopriv_wp_bouncer_check", [$this, "ajax_check"]);
        add_action("wp_enqueue_scripts", [$this, "wp_enqueue_scripts"]);
        add_action("admin_enqueue_scripts", [$this, "wp_enqueue_scripts"]);
        add_action("login_head", [$this, "user_bounced_error"]);
    }

    /**
     * Carga el textdomain para traducciones.
     */
    public function textdomain() {
        load_plugin_textdomain("wp-bouncer", false, basename(dirname(__FILE__)) . "/languages");
    }

    /**
     * Maneja el rastreo de inicio de sesión, generando un nuevo ID de sesión.
     */
    public function login_track($user_login) {
        $new_session_id = md5($user_login . time());
        set_transient("wp_bouncer_session_$user_login", $new_session_id, DAY_IN_SECONDS);
        setcookie("wp_bouncer_session", $new_session_id, time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
    }

    /**
     * Verifica si la sesión actual es válida.
     */
    public function login_flag() {
        if (get_option("paretomed_disable_simultaneous_logins") && is_user_logged_in()) {
            $current_session_id = $_COOKIE["wp_bouncer_session"] ?? '';
            $stored_session_id = get_transient("wp_bouncer_session_" . wp_get_current_user()->user_login);

            if ($current_session_id !== $stored_session_id) {
                wp_logout();
                wp_redirect(wp_login_url());
                exit;
            }
        }
    }

    /**
     * Agrega un enlace para resetear las sesiones en la página de usuarios del admin.
     */
    public function user_row_actions($actions, $user) {
        if (current_user_can('manage_options')) {
            $actions['reset_sessions'] = '<a href="' . wp_nonce_url("users.php?action=reset_sessions&user_id={$user->ID}", 'reset_sessions') . '">Reset Sessions</a>';
        }
        return $actions;
    }

    /**
     * Resetea la sesión de un usuario cuando se solicita.
     */
    public function reset_session() {
        if (!empty($_REQUEST['action']) && $_REQUEST['action'] === 'reset_sessions' && check_admin_referer('reset_sessions')) {
            $user_id = $_REQUEST['user_id'] ?? 0;
            delete_transient("wp_bouncer_session_$user_id");
            add_action("admin_notices", function() { echo '<div class="updated"><p>Sessions reset successfully.</p></div>'; });
        }
    }

    /**
     * Muestra notificaciones en el área de administración.
     */
    public function admin_notices() {
        // Aquí se pueden añadir notificaciones personalizadas.
    }

    /**
     * Enqueue scripts necesarios para el funcionamiento del bouncer.
     */
    public function wp_enqueue_scripts() {
        if (get_option("paretomed_disable_right_click")) {
            wp_enqueue_script("disable-right-click", plugin_dir_url(__FILE__) . "js/disable-right-click.js", [], null, true);
        }
    }

    /**
     * Gestiona la respuesta de AJAX para comprobar sesiones.
     */
    public function ajax_check() {
        $flagged = $this->login_flag();
        wp_send_json(['flagged' => $flagged]);
    }

    /**
     * Muestra un mensaje de error en la pantalla de login si la sesión fue rebotada.
     */
    public function user_bounced_error() {
        if (isset($_REQUEST['bounced']) && $_REQUEST['bounced']) {
            global $error;
            $error = __('There was an issue with your login. Please try again.', 'wp-bouncer');
        }
    }
}
