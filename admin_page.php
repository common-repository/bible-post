<?php
$version = get_option("bp_hyno_version");
$bp_hyno_id_page = get_option("bp_hyno_id_page");

$cxn = get_option("bp_hyno_conexion");
$fopen_sel = $cxn == "fopen" ? 'checked=checked' : '';
$curl_sel = $cxn == "curl" ? 'checked=checked' : '';

$showversion = get_option("bp_hyno_mostrarversion");
$showversion_sel = $showversion == "1" ? 'checked' : '';
$hideversion_sel = $showversion == "0" ? 'checked' : '';

$import_content = get_option("bp_hyno_importar");
$import_content_sel = $import_content == "1" ? 'checked' : '';
$link_content_sel = $import_content == "0" ? 'checked' : '';

$hide_content = get_option("bp_hyno_estado");
$visible_content_sel = $hide_content == "visible" ? 'checked' : '';
$oculto_content_sel = $hide_content == "oculto" ? 'checked' : '';

$footnotes = get_option("bp_hyno_footnotes");
$footnotes_sel = $footnotes == "1" ? 'checked' : '';
$footnotes_no_sel = $footnotes == "0" ? 'checked' : '';

$h_estilo = get_option("bp_hyno_estilo");
$h_estilo_sel = $h_estilo == "1" ? 'checked' : '';
$h_estilo_no_sel = $h_estilo == "0" ? 'checked' : '';
?>
<div class="wrap">
    <form method="post" name="options" target="_self" id="options">
        <div id="icon-plugins" class="icon32"></div>
        <h2>
            <?php _e('Bible Post: Configuracion.', 'bible-post'); ?>
        </h2>
        <div id="poststuff">
            <div class="metabox-holder columns-2" id="post-body">
                <div id="post-body-content">
                    <div class="stuffbox" id="instrucciones_hyno">
                        <h3><?php _e('Instrucciones de Uso', 'bible-post'); ?></h3>
                        <div class="inside">
                            <ol>
                                <li><?php _e('Usar el widget para poner el Versiculo o seccion que gustes', 'bible-post'); ?></li>
                                <li><?php _e('Usa la etiqueta [bible-post-hyno] para poner un pasaje biblico en tus post:', 'bible-post'); ?><br />
                                    [bible-post-hyno versiculo="Filipenses 4:7" showversion="1" class="MiClase" estado="visible" version="rvr1960"] (Parametros abajo)</li>
                            </ol>
                        </div>
                    </div>
                    <div class="stuffbox" id="version_defecto">
                        <h3>
                            <?php _e('Valores Predeterminados', 'bible-post'); ?>
                        </h3>
                        <div class="inside">
                            <table cellpadding="0" class="links-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label for="bp_hyno_mostrarversion"><?php _e('Mostrar Version', 'bible-post'); ?></label></th>
                                        <td>
                                            <input type="radio" name="bp_hyno_mostrarversion" value="1" <?php echo $showversion_sel; ?> />
                                            <?php _e('Si', 'bible-post'); ?>
                                            <br />
                                            <input type="radio" name="bp_hyno_mostrarversion" value="0" <?php echo $hideversion_sel; ?> />
                                            <?php _e('No', 'bible-post'); ?><br />
                                            <em><?php _e('Mostrara la Version usada.', 'bible-post'); ?></em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="bp_hyno_importar"><?php _e('Modo de Insercion', 'bible-post'); ?></label></th>
                                        <td>
                                            <input type="radio" name="bp_hyno_importar" value="1" <?php echo $import_content_sel; ?> />
                                            <span style="color: #f00; font-style: italic;"><?php _e('<b>Importado:</b> Incluira el contenido al post/Base de Datos.', 'bible-post'); ?></span><br />
                                            <input type="radio" name="bp_hyno_importar" value="0" <?php echo $link_content_sel; ?> />
                                            <span style="color: #060; font-style: italic;"><?php _e('<b>Vinculado:</b> Cargara el contenido de manera Externa; Se incluye ShortCode a la base de datos.', 'bible-post'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="bp_hyno_estado"><?php _e('Estado Inicial', 'bible-post'); ?></label></th>
                                        <td>
                                            <input type="radio" name="bp_hyno_estado" value="visible" <?php echo $visible_content_sel; ?> />
                                            <span><?php _e('Visible', 'bible-post'); ?></span><br />
                                            <input type="radio" name="bp_hyno_estado" value="oculto" <?php echo $oculto_content_sel; ?> />
                                            <span><?php _e('Oculto', 'bible-post'); ?></span><br />
                                            <em><?php _e('Estado inicial para el pasaje biblico incluido.', 'bible-post'); ?></em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="bp_hyno_version"><?php _e('Version por defecto', 'bible-post'); ?></label></th>
                                        <td>
                                            <select name="bp_hyno_version">
                                                <?php
                                                foreach ($this->versions as $num => $name) {
                                                    $sel = $version == $num ? 'selected' : '';
                                                    echo '<option value="' . $num . '" ' . $sel . '>' . $name . '</option>' . "\r\n";
                                                }
                                                ?>
                                            </select>
                                            <br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="bp_hyno_footnotes"><?php _e('Usar referencias cruzadas', 'bible-post'); ?></label></th>
                                        <td>
                                            <input type="radio" name="bp_hyno_footnotes" id="h_footnotes" value="1" <?php echo $footnotes_sel; ?> />
                                            <?php _e('Si', 'bible-post'); ?>
                                            <script type="text/javascript">
                                                jQuery(document).ready( function() {
                                                    jQuery('input:radio[name=bp_hyno_footnotes]').click(function(){
                                                        if(jQuery('#h_footnotes').is(':checked')){
                                                            jQuery('#txt_id_pag').show("slow");
                                                        }
                                                        else{
                                                            jQuery('#txt_id_pag').hide("slow");
                                                        }
                                                    }); 
                                                });
                                            </script>
                                            <?php
                                            $contenido_mostrar = '';
                                            if($footnotes != '1'){
                                                $contenido_mostrar = "style='display: none;' ";
                                            }
                                            ?>
<div id="txt_id_pag" <?php echo $contenido_mostrar; ?>><b>(</b>
    <input type="text" name="bp_hyno_id_page" style="width: 50px;" value="<?php echo $bp_hyno_id_page; ?>" />
                                            <span style="color: #060; font-style: italic;">
                                            <?php _e(' Id de pagina para referenias', 'bible-post'); ?>
                                            </span>
                                            <b>)</b>
</div>
                                            <?php
                                            //}
                                            ?>
                                            <br />
                                            <input type="radio" name="bp_hyno_footnotes" value="0" <?php echo $footnotes_no_sel; ?> />
                                            <?php _e('No', 'bible-post'); ?><br />
                                            <em><?php _e('Es necesario configurar ID de pagina de referencias.', 'bible-post'); ?></em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="bp_hyno_estilo"><?php _e('Texto Plano', 'bible-post'); ?></label></th>
                                        <td>
                                            <input type="radio" name="bp_hyno_estilo" value="0" <?php echo $h_estilo_no_sel; ?> />
                                            <?php _e('Si', 'bible-post'); ?>
                                            <br />
                                            <input type="radio" name="bp_hyno_estilo" value="1" <?php echo $h_estilo_sel; ?> />
                                            <?php _e('No', 'bible-post'); ?>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="stuffbox" id="modo_insercion">
                        <h3>
                            <?php _e('Configuracion de Conexion', 'bible-post'); ?>
                        </h3>
                        <div class="inside">
                            <em><p> <?php _e('Seleccione el modo de conexion que mas le convenga.', 'bible-post'); ?></p></em>
                            <input type="radio" name="bp_hyno_conexion" value="fopen" <?php echo $fopen_sel; ?> />
                            fopen<br />
                            <input type="radio" name="bp_hyno_conexion" disabled="disabled" value="curl" <?php echo $curl_sel; ?> />
                            curl<br />
                        </div>
                    </div>
                </div>
                <!-- /post-body-content -->
                <div class="postbox-container" id="postbox-container-1">
                    <div class="meta-box-sortables ui-sortable" id="side-sortables">
                        <div class="postbox " id="linksubmitdiv">
                            <h3 class="hndle"><span>
                                    <?php _e('Guardar Configuracion', 'bible-post'); ?>
                                </span></h3>
                            <div class="inside">
                                <div id="submitlink" class="submitbox">
                                    <div id="minor-publishing">
                                        <div id="misc-publishing-actions">
                                            <p class="popular-tags"><?php _e('Hola, espero que disfruten de mi plugin. Me llevó un montón de horas para hacerlo. Una gran cantidad de galletas y jugo de uva que se derramó sobre el teclado en la creación de este plugin. Si te gusta, me podrías ayudar invitandome un café recién hecho.', 'bible-post'); ?> </p>
                                            <div class="misc-pub-section center">
                                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                                    <input type="hidden" name="cmd" value="_s-xclick">
                                                    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHXwYJKoZIhvcNAQcEoIIHUDCCB0wCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYAVzzK0no/Tns0BQim7N5Ru9R4yxhVJsIcvdqZf50QMKt5v7bBN6SL9xxE+ZODBUjmQQQSHswtAzqzC6HWHnUhKToos5ZQ3qI/7slQIa6BOFqLmlBis/iNYWCPa1Fm5b4zia3TIPSOY1pVuJyN14BV/7Qit5N0Vsm4B1Hv3gugh7TELMAkGBSsOAwIaBQAwgdwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIQoKROS5GdUKAgbirQkKCDwg+I3YjAqteAbZ241UU7ftUNDhl17M5HNC+LqFnvScDXyDIJR9/c1iz0WOlB/hBNyuYa9AMSyneRIx9w13ZyWChH7/NL0wQkV37kH0GT1JMA0meN48TAE3jaPEDcG/mctOeV/SgF0mFM3LqCQ850Qm/Th6Brg/1wwCk2Y0aMSnvfl1U48sD4d80ct78q0glQrCuEav3VgUOrm0sYodjmddVKDMA6E5Gchqs3UKGr5dXtgzuoIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMTIwNzA1MDEyNDA0WjAjBgkqhkiG9w0BCQQxFgQUuVWAXzGgY/gXJQKzp9zq7oWq4DMwDQYJKoZIhvcNAQEBBQAEgYBI0cFXka9jEkbki1YICf+ZDFtTnMdqf+0wPJaPKkP7OiH1N+Ea9cc+UOgnpCzOBZR3EkyMkUX1GLEK+eAfrQtUTys3Rgy8ECk8LzEPFlIfTA52ODB4MgL4CiWdEOQquxqKECdkjnX6+vfRTrhd9Gag7cdcrCFq9EMC1KugA6mHwg==-----END PKCS7-----
                                                           ">
                                                    <input type="image" src="<?php echo plugins_url('img/paypal_200x96.png', __FILE__); ?>" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                                    <img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="major-publishing-actions">
                                        <div id="delete-action"> <a href="http://www.hyno.ok.pe" target="_blank">HynoTech Web</a> </div>
                                        <div id="publishing-action">
                                            <input type="submit" value="<?php _e('Guardar', 'bible-post'); ?>" tabindex="4" id="publish" class="button-primary" name="bp_hyno_update" />
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="postbox-container" id="postbox-container-2">
                    <div class="meta-box-sortables ui-sortable" id="advanced-sortables">
                        <hr />
                        <div class="stuffbox" id="modo_insercion">
                            <h3>
                                <?php _e('Parametros de Shortcode', 'bible-post'); ?>
                            </h3>
                            <div class="inside">
                                <p class="popular-tags"><em>[bible-post-hyno versiculo="Filipenses 4:7" showversion="1" class="MiClase" estado="visible" version="rvr1960"]</em></p>
                                <p class="popular-tags"><em>[bible-post-hyno versiculo="Filipenses 4:7" showversion="1" contenedor="0" version="rvr1960"]</em></p>
                                <p class="popular-tags"><em>[bible-post-hyno versiculo="Filipenses 4:7" showversion="1" ref_cruzadas="1" version="rvr1960"]</em></p>

                                <table cellpadding="0" class="links-table">
                                    <tbody>
                                        <tr>
                                            <th scope="row">versiculo</label></th>
                                            <td><?php _e('Pasaje Biblico a publicar.', 'bible-post'); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">showversion</label></th>
                                            <td>
                                                <em><?php _e('Mostrar version al publicar el pasaje', 'bible-post'); ?></em><br />
                                                <ul>
                                                    <li>0 = <?php _e('No Mostrar <em>(Default)</em>', 'bible-post'); ?></li>
                                                    <li>1 = <?php _e('Mostrar', 'bible-post'); ?></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">class</th>
                                            <td>
                                                <em><?php _e('Asignar clase personalizada (por defecto: hynoshortcode)', 'bible-post'); ?></em>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">estado</th>
                                            <td>
                                                <em><?php _e('Estado del pasaje en publicacion', 'bible-post'); ?></em><br />
                                                <ul>
                                                    <li><?php _e('Visible: Muestra el contenido directamente<em>(Default)</em>', 'bible-post'); ?></li>
                                                    <li><?php _e('Oculto: Es necesario hacer click para que pueda ser visto', 'bible-post'); ?></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">version</th>
                                            <td>
                                                <select multiple disabled>
                                                    <?php
                                                    foreach ($this->versionCodes as $num => $name) {

                                                        $txt_versiones = $this->versions[$name];
                                                        echo '<option value="' . $num . '">(code: ' . $num . ') ' . $txt_versiones . '</option>' . "\r\n";
                                                    }
                                                    ?>
                                                </select>
                                                <br />
                                                <?php _e('o codigos(ID) de ', 'bible-post'); ?><a target="_blank" href="http://www.biblegateway.com/usage/linking/versionslist.php">biblegateway.com</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="bp_hyno_estado">contenedor</label></th>
                                            <td>
                                                <em><?php _e('Usa o no el estilo por defecto del plugin', 'bible-post'); ?></em><br />
                                                <ul>
                                                    <li>0 = <?php _e('Usa el estilo del Plugin <em>(Default)</em>', 'bible-post'); ?></li>
                                                    <li>1 = <?php _e('Solo TEXTO PLANO', 'bible-post'); ?></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="bp_hyno_estado">ref_cruzadas</label></th>
                                            <td>
                                                <em><?php _e('Usa Referencias a otra seccion de la biblia que hable de lo mismo', 'bible-post'); ?></em><br />
                                                <ul>
                                                    <li>0 = <?php _e('Usa referencias <em>(Default)</em>', 'bible-post'); ?></li>
                                                    <li>1 = <?php _e('Esconde referencias', 'bible-post'); ?></li>
                                                </ul>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
