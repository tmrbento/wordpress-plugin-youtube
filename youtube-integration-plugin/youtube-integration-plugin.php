<?php
/*
Plugin Name: YouTube Integration Plugin
Description: Integra com a API do YouTube e cria páginas para vídeos com título "aula".
Version: 1.1
Author: Seu Nome
*/

// Se não for o WordPress, saia
if (!defined('ABSPATH')) {
    exit;
}

// Autoload das classes
require_once plugin_dir_path(__FILE__) . 'includes/YouTubeAPI.php';
require_once plugin_dir_path(__FILE__) . 'includes/VideoPageCreator.php';

// Hook para adicionar menu no admin
add_action('admin_menu', 'youtube_integration_add_admin_menu');

function youtube_integration_add_admin_menu()
{
    add_menu_page(
        'YouTube Integration',        // Título da página
        'YouTube Integration',        // Título do menu
        'manage_options',             // Permissões
        'youtube_integration',        // Slug do menu
        'youtube_integration_settings_page' // Função de renderização
    );
}

// Página de configurações
function youtube_integration_settings_page()
{
    ?>
    <div class="wrap">
        <h1>YouTube Integration Plugin</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('youtube_integration_settings');
            do_settings_sections('youtube_integration');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registro das configurações
add_action('admin_init', 'youtube_integration_register_settings');

function youtube_integration_register_settings()
{
    // Adiciona as configurações
    register_setting('youtube_integration_settings', 'youtube_api_key');
    register_setting('youtube_integration_settings', 'youtube_channel_id');

    // Adiciona a seção
    add_settings_section(
        'youtube_integration_main_section',
        'Configurações da API do YouTube',
        null,
        'youtube_integration'
    );

    // Adiciona o campo para a API Key
    add_settings_field(
        'youtube_api_key_field',
        'Chave da API do YouTube',
        'youtube_api_key_callback',
        'youtube_integration',
        'youtube_integration_main_section'
    );

    // Adiciona o campo para o ID do Canal
    add_settings_field(
        'youtube_channel_id_field',
        'ID do Canal do YouTube',
        'youtube_channel_id_callback',
        'youtube_integration',
        'youtube_integration_main_section'
    );

    add_settings_field(
        'youtube_channel',
        'Videos:',
        'youtube_listvideos',
        'youtube_integration',
        'youtube_integration_main_section'
    );
}

// Callback para a API Key
function youtube_api_key_callback()
{
    $api_key = get_option('youtube_api_key');
    echo '<input type="text" name="youtube_api_key" value="' . esc_attr($api_key) . '" style="width: 400px;">';
}

// Callback para o ID do Canal
function youtube_channel_id_callback()
{
    $channel_id = get_option('youtube_channel_id');
    echo '<input type="text" name="youtube_channel_id" value="' . esc_attr($channel_id) . '" style="width: 400px;">';
}

function youtube_listvideos()
{
    $apiKey = get_option('youtube_api_key');
    $channelId = get_option('youtube_channel_id');

    if (!$apiKey || !$channelId) {
        return; // Sai caso as credenciais não estejam configuradas
    }

    // Instanciando a classe da API e criador de páginas
    $youtubeAPI = new YouTubeAPI($apiKey);

    // Buscar vídeos com título "aula"
    $videos = $youtubeAPI->getVideosByChannel($channelId, 'aula');
    echo '<table width="300px" bgcolor="#FFFFFF">';
    echo '<tr><td><b>ID</b></td><td><b>Nome do Vídeo</b></td></tr>';
    if ($videos) {
        $videoPageCreator = new VideoPageCreator();
        foreach ($videos as $video) {
            $videoPageCreator->createPageForVideo($video['title'], $video['url']);
            echo '<tr><td>' . $video['id'] . '</td><td>' . $video['title'] . '</td></tr>';
        }
    } else {
        echo '<tr><td colspan="2">Nenhum vídeo</td></tr>';
    }
    echo '</table>';
}

// Função principal que usa as configurações salvas
function youtube_integration_run()
{
    $apiKey = get_option('youtube_api_key');
    $channelId = get_option('youtube_channel_id');

    if (!$apiKey || !$channelId) {
        return; // Sai caso as credenciais não estejam configuradas
    }

    // Instanciando a classe da API e criador de páginas
    $youtubeAPI = new YouTubeAPI($apiKey);
    $videoPageCreator = new VideoPageCreator();

    // Buscar vídeos com título "aula"
    $videos = $youtubeAPI->getVideosByChannel($channelId, 'aula');

    // Criar páginas para cada vídeo
    foreach ($videos as $video) {
        $videoPageCreator->createPageForVideo($video['title'], $video['url']);
    }
}

// Agendamento do cron job
add_action('wp_loaded', function () {
    if (!wp_next_scheduled('youtube_integration_hourly')) {
        wp_schedule_event(time(), 'hourly', 'youtube_integration_hourly');
    }
});

add_action('youtube_integration_hourly', 'youtube_integration_run');
