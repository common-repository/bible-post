<?php


if (isset($_POST["txt_versiculo_p"])) {
    include_once('simple_html_dom.php');
    //,chk_contenedor,chk_referencias
    $versiculo = $_POST["txt_versiculo_p"];
    $version = $_POST["txt_version_p"];
    $showVersion = $_POST["txt_mostrarversion_p"];
    $clase = $_POST["txt_clase_p"];
    $estado = $_POST["rbt_hide_p"];
    
    //, $h_estilo,$id_page
    $h_estilo = $_POST["chk_contenedor"];
    $footnotes = $_POST["chk_referencias"];
    $id_page = $_POST["id_page"];

    $url = "http://www.biblegateway.com/passage/?search=" . urlencode($versiculo) . "&version=$version";
    //txt_version_p=60&txt_versiculo_p=Josue 1:8-9&txt_clase_p=&txt_mostrarversion_p=&rbt_hide_p=visible

    if ($fp = fopen($url, 'r')) {
        $content = stream_get_contents($fp);
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
        }
        $data = "<div class='" . $clase . "' style='text-align:justify;'>$verse</div>";
    } else {
        $data = "error";
    }
    echo $data;
}
?>