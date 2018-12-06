<?php

require_once __DIR__ . '/vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->get('/list', function (Request $request) use ($app) {
    // get params
    $parent = $request->get('parent');

    if (!empty($parent)) {
        // craws links
        $crawler = new Crawler($parent);
        $links = $crawler->getLinks();

        // filter links if needed
        $filtered = array_values(array_filter($links, function ($link) {
            return preg_match('/.pdf$/', $link);
        }));

        if (!empty($filtered)) {
            // search for common cases
            $filtered = array_map(function ($link) {
                $newLink = preg_replace('/\/article\/view\/(\d+)\/pdf/', '/article/download/$1/pdf', $link);
                return $newLink;
            }, $filtered);

            $links = $filtered;
        }
    }

//    $links = array_slice($links, 0, 3);
    $links = array_values($links ?: []);
    return $app->json($links);
});

$app->get('/item', function (Request $request) use ($app) {

    // get params
    $url = $request->get('url');

    // download page page
    $crawler = new Crawler($url);
    $contents = $crawler->getContents();

    // parse emails
    if (!empty($contents)) {
        $parser = new EmailParser($contents);
        $emails = $parser->parseEmails();
    }

    return $app->json(@$emails ?: []);
});

$app->run();
