<?php
class YouTubeAPI
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getVideosByChannel($channelId, $keyword)
    {
        $url = "https://www.googleapis.com/youtube/v3/search?key={$this->apiKey}&channelId={$channelId}&part=snippet&type=video&maxResults=50";
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return [];
        }

        $videos = json_decode(wp_remote_retrieve_body($response), true);
        $filteredVideos = [];

        foreach ($videos['items'] as $item) {
            $title = $item['snippet']['title'];
            if (stripos($title, $keyword) !== false) {
                $filteredVideos[] = [
                    'title' => $title,
                    'id' => $item['id']['videoId'],
                    'url' => 'https://www.youtube.com/embed/' . $item['id']['videoId']
                ];
            }
        }

        return $filteredVideos;
    }
}
