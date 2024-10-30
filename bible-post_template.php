<?php
/**
 * Template name: La Santa Biblia(BiblePost)
 */
get_header();
?>

<div id="container">
    <div id="content" role="main">
        <h1 class="title">La Santa Biblia</h1>

        <?php
        if (isset($_GET['search'])) {
            $cadena_busqueda = $_GET['search'];
            $version_busqueda = strtolower($_GET['version']);
            echo do_shortcode('[bible-post-hyno version="' . $version_busqueda . '" showversion="1" class="myclass" versiculo="' . $cadena_busqueda . '"]');
            //[bible-post-hyno versiculo="Filipenses 4:7" showversion="1" class="MiClase" estado="visible" version="rvr1960"]
            //echo do_shortcode('');
        } else {
            echo "<h2>";
            _e('Pagina no Preparada', 'bible-post');
            echo "</h2>";
        }
        echo "<center>";
        _e('La Biblia Gracias a:', 'bible-post');
        echo "<br />";
        ?>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="middle" style="background:#651300"><a href="http://www.biblegateway.com" target="_new"><img src="http://static5.bgcdn.com/images/logos/logo_transbg.png" width="281" height="57" /></a></td>
            </tr>
        </table>
<?php
echo "</center>";
?>

    </div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>