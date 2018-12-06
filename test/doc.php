<?php

require_once __DIR__ . '/../web/silex/vendor/autoload.php';

$url = 'http://kaznpu.kz/docs/vestnik/filologicheskie_nauki/philology_2017.2.pdf';

$crawler = new Crawler($url);
$contents = $crawler->getContents();

$parser = new EmailParser($contents);
$text = $parser->pdf2text();
$emails = $parser->parseEmails();
