<?php

/**
 * Plugin Name: Bible Post
 * Plugin URI: http://www.hyno.ok.pe/seccion/wp-plugins/bible-post/
 * Description: Plugin que permitira incluir secciones de la biblia en las versiones mas conocidas en diferentes idiomas. Cualquie duda, sugerencia o fallo, por favor comunicarlo a la pagina del autor. Gracias a http://www.biblegateway.com/ por poner a disposicion la Biblia Online.
 * Version: 2.2
 * Author: Antonio Salas (Hyno)
 * Author URI: http://www.hyno.ok.pe
 * License:     GNU General Public License
 */

include_once('simple_html_dom.php');
if (!class_exists("BiblePost")) {

    class BiblePost {

        public $versions;
        public $versionCodes;

        function __construct() {
            require ('bible-versions.php');
            $this->versions = $versions;
            $this->versionCodes = $versionCodes;
			//add_action('init', 'load_plugin_textdomain');
			add_action('init', array($this, 'load_plugin_textdomain'));
			
        }
		public function load_plugin_textdomain()
		{
			load_plugin_textdomain('bible-post', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
		}
        function crear_meta_user_error($tag) {
            global $current_user;
            $id_usuario = $current_user->ID;
            add_user_meta($id_usuario, $tag, 'true', true);
        }

        function activate() {
            // add options to database
            add_option("bp_hyno_version", '60');
            add_option("bp_hyno_importar", '0');
            add_option("bp_hyno_estado", 'visible');
            add_option("bp_hyno_conexion", 'fopen');
            add_option("bp_hyno_mostrarversion", '1');
            add_option("bp_hyno_estilo", '1');
            add_option("bp_hyno_footnotes", '1');
            add_option("bp_hyno_id_page", '8');
            

            $archivo_theme_biblepost = get_stylesheet_directory() . "/bible-post_template.php";
            $archivo_origen_thema = WP_PLUGIN_DIR . "/bible-post/bible-post_template.php";
            if (file_exists($archivo_theme_biblepost)) {
                if (@unlink($archivo_theme_biblepost)) {
                    @copy($archivo_origen_thema, $archivo_theme_biblepost);
                }
            } else {
                @copy($archivo_origen_thema, $archivo_theme_biblepost);
            }
            //$this->add_style_2_theme();
        }

        function deactivate() {
            // remove options from database
            delete_option("bp_hyno_version");
            delete_option("bp_hyno_importar");
            delete_option("bp_hyno_estado");
            delete_option("bp_hyno_conexion");
            delete_option("bp_hyno_mostrarversion");
            delete_option("bp_hyno_footnotes");
            delete_option("bp_hyno_estilo");
            delete_option("bp_hyno_id_page");

            global $current_user;
            $id_usuario = $current_user->ID;
        }

        function add_admin_page() {
            add_submenu_page('options-general.php', '(Hyno)Bible Post', 'Bible Post', 10, __file__, array(&$this, 'admin_page'));
        }

        function admin_page() {
            // update settings
            if (isset($_POST['bp_hyno_update'])) {

                // posted data
                $version = $_POST['bp_hyno_version'];
                $import_conten = $_POST['bp_hyno_importar'];
                $showhide = $_POST['bp_hyno_estado'];
                $cxn = $_POST['bp_hyno_conexion'];
                $showver = $_POST['bp_hyno_mostrarversion'];
                $footnotes = $_POST['bp_hyno_footnotes'];
                $h_estilo = $_POST['bp_hyno_estilo'];
                $id_page = $_POST['bp_hyno_id_page'];

                // update data in database
                update_option("bp_hyno_version", $version);
                update_option("bp_hyno_importar", $import_conten);
                update_option("bp_hyno_estado", $showhide);
                update_option("bp_hyno_conexion", $cxn);
                update_option("bp_hyno_mostrarversion", $showver);
                update_option("bp_hyno_footnotes", $footnotes);
                update_option("bp_hyno_estilo", $h_estilo);
                update_option("bp_hyno_id_page", $id_page);
                

                // updated message
                echo "<div id=\"message\" class=\"updated fade\"><p><strong>"; 
                _e('Opciones de Bible Post Actualizados.', 'bible-post'); 
                echo "</strong></p></div>";
            }
            require_once ('admin_page.php');
        }

        function widget_init() {
            if (!function_exists('register_sidebar_widget') || !function_exists('register_widget_control'))
                return;

            register_widget('BiblePostWidget');
        }

        function add_js() {
            echo file_get_contents(ABSPATH . 'wp-content/plugins/bible-post/functions.js');
        }

    }

class BiblePostWidget extends WP_Widget {

    public $versions;
    public $versionCodes;

    function BiblePostWidget() {
        require ('bible-versions.php');
        $this->versions = $versions;
        $this->versionCodes = $versionCodes;

        $widget_ops = array('classname' => 'bp_hyno', 'description' => __('Widget Versiculo Biblico en Post'));
        $control_ops = array('id_base' => 'bp_hyno-widget');

        $this->WP_Widget('bp_hyno-widget', __('Widget Versiculo Biblico', 'bible-post'), $widget_ops, $control_ops);
    }

    function replace_shortcode($atts) {
        // set defaults 
        extract(shortcode_atts(array(
                    'version' => get_option('bp_hyno_version'),
                    'estado' => get_option('bp_hyno_estado'),
                    'showversion' => get_option('bp_hyno_mostrarversion'), 
                    'ref_cruzadas' => get_option('bp_hyno_footnotes'), 
                    'contenedor' => get_option('bp_hyno_estilo'), 
                    'id_page' => get_option('bp_hyno_id_page'),
                    'class' => 'hynoshortcode',
                    'versiculo' => 'Juan 3:16'
                    ), $atts));
        return $this->get_verse($versiculo, $version, $showversion, $ref_cruzadas, $contenedor,$id_page, $estado, $class);
    }

    function biblegateway_version($version) {
        // $version can't be an integer
        if (preg_match('/^[0-9]+$/', $version))
            return $version;

        // return chosen version: default to NIV
        return array_key_exists($version, $this->versionCodes) ? $this->versionCodes[$version] :
                3;
    }

    function fetch_url($url) {
        switch (get_option('bp_hyno_conexion')) {
            case "curl":
                if (function_exists("curl_init")) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    curl_close($ch);
                    return $output;
                }
                break;
            case "fopen": // falls through
            default:
                return ($fp = fopen($url, 'r')) ? stream_get_contents($fp) : false;
                //return file_get_html($url);
                break;
        }

        return false;
    }

    function get_verse($versiculo, $version, $showVersion, $footnotes, $h_estilo,$id_page, $estado = "visible", $class = 'bvdwidget') {
        $favorites = explode('|', get_option('bvd_favorites', ''));
        $lookup = urlencode($versiculo);
        $ver = $this->biblegateway_version($version); // get biblegateway ID if a code was used
        $url = "http://www.biblegateway.com/passage/?search=$lookup&version=$ver";
        //$url = "http://www.biblegateway.com/passage/?search=Juan%203:16&version=RVR1960";

        $content = $this->fetch_url($url);
        if ($content != "") {
            $htmlCode = str_get_html($content);
            $e = $htmlCode->find('div[class=passage-left]', 0);

            if ($e) {
                $div_contenedor = str_get_html($e->innertext);

                $elim = $div_contenedor->find('ul');

                foreach ($elim as $tag_ul) {
                    $tag_ul->outertext = '';
                }
                foreach ($div_contenedor->find('.passage-scroller,.commentary-link,.publisher-info-bottom') as $tag_div_footnotes) {
                    $tag_div_footnotes->outertext = '';
                }

                $array_titulos = array();

                $array_titulos['pasaje'] = $div_contenedor->find('div h3', 0)->innertext;

                foreach ($div_contenedor->find('p.txt-sm') as $txt_version) {
                    $array_titulos['version'] = "(" . $txt_version->innertext . ")";
                    $txt_version->outertext = '';
                }
                foreach ($div_contenedor->find('.heading') as $txt_version) {
                    $txt_version->outertext = '';
                }
                foreach ($div_contenedor->find('.passage-parallel-button, .passage-other-trans') as $txt_version) {
                    $txt_version->outertext = '';
                }
                if($footnotes != 1){
                    foreach ($div_contenedor->find('.crossrefs') as $txt_version) {
                        $txt_version->outertext = '';
                    }
                }
                foreach ($div_contenedor->find('.footnotes strong') as $txt_version) {
                    $txt_version->innertext = 'Notas:';
                }
                foreach ($div_contenedor->find('.crossrefs strong') as $txt_version) {
                    $txt_version->innertext = 'Referencias:';
                }
                foreach ($div_contenedor->find('comment') as $txt_version) {
                    $txt_version->outertext = '';
                }
                foreach ($div_contenedor->find('div[style*="clear:both;"]') as $txt_version) {
                    $txt_version->outertext = '';
                }
                /*echo "<textarea cols=80 rows=10>".$div_contenedor."</textarea>";
                foreach ($div_contenedor->find('div[class*="passage"]') as $txt_version) {
                    $txt_version->outertext = '';
                }*/
                foreach ($div_contenedor->find('.footnote') as $tag_div_class) {
                    $tag_div_class->value = null;
                }

                foreach ($div_contenedor->find('.crossrefs li a') as $tag_a_ref) {
                    //$tag_a_ref->outertext = '';
                    $ref_link = $tag_a_ref->href;
                    $primera_ref_link = substr($ref_link, 0, 1);
                    if ($primera_ref_link == "/") {
                        $lo_demas_ref_link = substr($ref_link, 1, strlen($ref_link));
                        $pos = strpos($lo_demas_ref_link, '&');
                        $antes_de_param = substr($lo_demas_ref_link, $pos - 1, 1);
                        
                        if ($antes_de_param != '.') {
                            $parte1 = substr($lo_demas_ref_link, 0, $pos);
                            $parte2 = substr($lo_demas_ref_link, $pos, strlen($lo_demas_ref_link) - $pos);
                            $parte1 = str_replace("passage/?", "?page_id=".$id_page."&", $parte1);
                            $nuevo_lnk = $parte1 . $parte2;
                        } else {
                            //$tag_a_ref->outertext = '';
                            $nuevo_lnk = $lo_demas_ref_link;
                        }
                        $tag_a_ref->href = $nuevo_lnk;
                    }
                }

                $n_txt = str_replace('<p />', '<br />', $div_contenedor->outertext);
                //echo "<textarea cols=80 rows=10>".$n_txt."</textarea>";
                if ($estado == "visible") {
                    $lbl_mostar = "block";
                    $open_txt = " open";
                } else {
                    $lbl_mostar = "none";
                    $open_txt = "";
                }
                if ($showVersion != 1) {
                    $array_titulos['version'] = "";
                }
                if($h_estilo != 0){

                    $verse = '<div class="hyno_learn_more clearfix"><div class="img_bible">';
                    $verse .= '<h3 class="heading-more' . $open_txt . '"><span>' . $array_titulos['pasaje'] . ' <span class="bible_version"> ' . $array_titulos['version'] . '</span></span></h3>';
                    $verse .= '<div class="learn-more-content" style="visibility: visible; display: ' . $lbl_mostar . ';">' . $n_txt . '</div>';
                    $verse .= '</div></div>';
                }
                else{
                    $verse = "<h5><strong>".$array_titulos['pasaje']."</strong><i>".$array_titulos['version']."</i></h5>";
                    $verse .= "<span style='font-size: 80%;'>".$n_txt."</span>";
                }

            } else {
                $verse = __("No podemos Revisar ", 'bible-post') . urldecode($lookup) . " ($url).";
                
            }
        } else {
            $verse = __("Versiculo no encontrado.", 'bible-post');
        }

        return "<div class='" . $class . "' style='text-align:justify;'>$verse</div>";
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', $instance['title']);
        $version = $instance['version'];
        $showVersion = $instance['showVersion'];
        $citaBiblica = $instance['citaBiblica'];

        echo $before_widget;

        if ($title)
            echo $before_title . $title . $after_title;

        if ($citaBiblica && $version)
            echo BiblePostWidget::get_verse($citaBiblica, $version, $showversion); //get_verse($type, $version, $showVersion);

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['version'] = $new_instance['version'];
        $instance['showVersion'] = $new_instance['showVersion'];
        $instance['citaBiblica'] = $new_instance['citaBiblica'];


        return $instance;
    }

    function form($instance) {

        $defaults = array('title' => 'Versiculo Favorito', 'version' => '60',
            'showVersion' => 1, 'type' => 'fav', 'showDate' => '0', 'dateFormat' => 'y-m-d');
        $instance = wp_parse_args((array) $instance, $defaults);

        include ("widget_form.php");
    }

}
    
}

function verifica_instalacion() {
    //e_editor-style_no_encontrado//e_editor-style_no_editable//e_styles-hyno_no_encontrado//e_instalacion
    global $current_user;
    $user_id = $current_user->ID;
    $mensaje = array();
    //if (get_user_meta($user_id, 'error_hyno_tmp') == true)
     //       $mensaje[] = "ERROR";
    if (get_user_meta($user_id, 'e_editor-style_no_encontrado') == true)
        $mensaje[] = 'No se encuentra el archivo ' . get_stylesheet_directory() . "/editor-style.css" . ' cree este archivo en la carpeta principal de su theme';
    if (get_user_meta($user_id, 'e_editor-style_no_editable') == true)
        $mensaje[] = 'El archivo ' . get_theme_root_uri() . '/editor-style.css No tiene permisos de edicion, asignele estos permisos antes de proseguir';
    if (get_user_meta($user_id, 'e_styles-hyno_no_encontrado') == true)
        $mensaje[] = 'Error del paquete descargado. Pruebe descargar/instalar Bible Post nuevamente. Si el problema persiste, por favor reportelo.';
    if (get_user_meta($user_id, 'e_instalacion') == true)
        $mensaje[] = 'Error del Plugin. Pruebe descargar/instalar Bible Post nuevamente. Si el problema persiste, por favor reportelo.';

    if (count($mensaje) > 0) {
        echo '<div class="error">';
        echo '<p><strong><em>Bible Post:</em></strong></p>';
        foreach ($mensaje as $value) {
            echo "<p>" . addcslashes($value,'"') . "</p>";
        }
        echo "</div>";
    }
}

function conditionally_add_scripts_and_styles($posts) {
    if (empty($posts))
        return $posts;

    $shortcode_found = false; // usamos shortcode_found para saber si nuestro plugin esta siendo utilizado

    foreach ($posts as $post) {

        if (stripos($post->post_content, 'bible-post-hyno')) { //cambiamos testiy por cualquier shortcode
            $shortcode_found = true; // bingo!
            break;
        }

        if (stripos($post->post_content, 'hyno_learn_more')) { //cambiamos testiy por cualquier shortcode
            $shortcode_found = true; // bingo!
            break;
        }
    }
    if ($shortcode_found) {
        // enqueue
        wp_enqueue_script('jquery');
        wp_enqueue_style('bible-post-style', plugins_url("bible-post/") . 'styles-hyno.css'); //la ruta de nuestro css
        wp_enqueue_script('bible-post-script', plugins_url('scripts-hyno.js', __FILE__)); //en caso de necesitar la ruta de nuestro script js
    }

    return $posts;
}

function biblepost_add_button($buttons) {
    array_push($buttons, "|", "biblepost");
    return $buttons;
}

function biblepost_register_button($plugin_array) {
    $url_biblepost = WP_PLUGIN_URL . "/bible-post/script/biblepost.js";
    $plugin_array['biblepost'] = $url_biblepost;
    return $plugin_array;
}

function plugin_mce_css( $mce_css ) {
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= plugins_url( 'styles-hyno.css', __FILE__ );

	return $mce_css;
}
function myplugin_init() {
	//load_plugin_textdomain("bible-post", false, dirname(plugin_basename(__FILE__)) . '/lang/');
	load_plugin_textdomain("bible-post", false, plugins_url('languajes/', __FILE__));
}




add_filter( 'mce_css', 'plugin_mce_css' );

add_action('admin_init', 'verifica_instalacion');
// instantiate class
if (class_exists("BiblePost")) {
    $biblepost = new BiblePost();
    $bvwidget = new BiblePostWidget();
}
// actions/filters
if (isset($biblepost)) {
    
    
    add_filter('mce_external_plugins', "biblepost_register_button");
    add_filter('mce_buttons', 'biblepost_add_button', 0);
    add_filter('the_posts', 'conditionally_add_scripts_and_styles'); // the_posts es lanzando antes que el header
    // administrative options
    add_action('admin_menu', array(&$biblepost, 'add_admin_page'));
    add_action("widgets_init", array(&$biblepost, 'widget_init'));

    // shortcodes
    //add_shortcode('bible-verse-display', array(&$biblepost, 'replace_shortcode'));
    add_shortcode('bible-post-hyno', array(&$bvwidget, 'replace_shortcode'));

    // activate/deactivate
    register_activation_hook(__file__, array(&$biblepost, 'activate'));
    register_deactivation_hook(__file__, array(&$biblepost, 'deactivate'));
    
    
}
?>