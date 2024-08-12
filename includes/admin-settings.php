<?php
// Añadir menú de configuración
function paretomed_menu() {
    add_options_page(
        "Opciones de Paretomed",
        "Paretomed",
        "manage_options",
        "paretomed-extensiones",
        "paretomed_options_page"
    );
}

// Registrar la opción
function paretomed_register_settings() {
    register_setting(
        "paretomed_options_group",
        "paretomed_disable_right_click"
    );
    
    register_setting(
        "paretomed_options_group",
        "paretomed_disable_simultaneous_logins"
    );
}

// Página de opciones
function paretomed_options_page() {
    ?>
    <div class="wrap">
        <h2>Opciones de Paretomed</h2>
        <form method="post" action="options.php">
            <?php settings_fields("paretomed_options_group"); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Configuración de Seguridad:</th>
                    <td>
                        <input type="checkbox" id="paretomed_disable_right_click" name="paretomed_disable_right_click" value="1" <?php checked(1, get_option("paretomed_disable_right_click"), true); ?> />
                        <label for="paretomed_disable_right_click">Desactivar clic derecho y otros atajos de seguridad</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Limitar sesiones simultáneas:</th>
                    <td>
                        <input type="checkbox" id="paretomed_disable_simultaneous_logins" name="paretomed_disable_simultaneous_logins" value="1" <?php checked(1, get_option("paretomed_disable_simultaneous_logins"), true); ?> />
                        <label for="paretomed_disable_simultaneous_logins">Limitar sesiones simultáneas</label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var checkbox = document.getElementById('paretomed_disable_right_click');
            checkbox.addEventListener('change', function() {
                var status = checkbox.checked ? 'Activado' : 'Desactivado';
                checkbox.nextSibling.textContent = 'Desactivar clic derecho y otros atajos de seguridad (' + status + ')';
            });
        });
    </script>
    <?php
}
?>
