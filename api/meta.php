<?php
function renderMetaTags($title, $description, $image = '', $url = '') {
  $title = htmlspecialchars($title);
  $description = htmlspecialchars($description);
  $image = $image ? htmlspecialchars($image) : 'https://yourdomain.com/assets/images/default-og.jpg';
  $url = $url ? htmlspecialchars($url) : 'https://yourdomain.com';

  echo <<<HTML
    <meta name="description" content="$description">
    <meta property="og:title" content="$title" />
    <meta property="og:description" content="$description" />
    <meta property="og:image" content="$image" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="$url" />
    <meta name="twitter:card" content="summary_large_image" />
HTML;
}