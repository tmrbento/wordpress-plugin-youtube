<?php
class VideoPageCreator
{
    public function createPageForVideo($title, $url)
    {
        // Verifica se uma página com o mesmo título já existe
        if (get_page_by_title($title)) {
            return;
        }

        // Conteúdo da página com o vídeo incorporado
        $content = "<h2>{$title}</h2>";
        $content .= "<iframe width='560' height='315' src='{$url}' frameborder='0' allowfullscreen></iframe>";

        // Criar a página no WordPress
        wp_insert_post([
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'page'
        ]);
    }
}
