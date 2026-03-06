<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// --- CONFIGURATIE ---
$access_token = 'INSTAGRAM_ACCESS_TOKEN_HIER';
$cache_file   = __DIR__ . '/instagram-cache.json';
$cache_time   = 900; // 15 minuten

// Lege response als token niet ingesteld
if ($access_token === 'INSTAGRAM_ACCESS_TOKEN_HIER' || empty($access_token)) {
    echo json_encode(['posts' => []]);
    exit;
}

// Serve vanuit cache als die recent genoeg is
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
    readfile($cache_file);
    exit;
}

// Haal posts op via Instagram Graph API
$url = 'https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,thumbnail_url,permalink,timestamp&limit=6&access_token=' . urlencode($access_token);
$response = file_get_contents($url);

if ($response === false) {
    // API mislukt - probeer token te refreshen
    $refresh_url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . urlencode($access_token);
    @file_get_contents($refresh_url);

    // Serve oude cache als die er is
    if (file_exists($cache_file)) {
        readfile($cache_file);
        exit;
    }
    echo json_encode(['posts' => []]);
    exit;
}

$data = json_decode($response, true);
$posts = [];

if (isset($data['data'])) {
    foreach ($data['data'] as $post) {
        $posts[] = [
            'id'      => $post['id'],
            'caption' => $post['caption'] ?? '',
            'type'    => $post['media_type'],
            'image'   => $post['media_type'] === 'VIDEO' ? ($post['thumbnail_url'] ?? '') : ($post['media_url'] ?? ''),
            'url'     => $post['permalink'],
            'date'    => $post['timestamp'],
        ];
    }
}

$output = json_encode(['posts' => $posts]);

// Schrijf cache
@file_put_contents($cache_file, $output);

echo $output;
