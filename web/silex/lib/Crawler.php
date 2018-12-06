<?php

use Symfony\Component\DomCrawler;
use Symfony\Component\HttpFoundation;

/**
 * Just another crawler
 */
class Crawler
{
    protected $link;
    protected $contents;
    protected $prefix;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public static function createFromRequest(Request $request)
    {
        // @todo
    }

    public function getContents()
    {
        if (!empty($contents)) {
            return $contents;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($this->link);
            $this->contents = $response->getBody()->getContents();
        } catch (\Exception $exception) {
            return;
        }

        return $this->contents;
    }

    protected function isLinkFull($link)
    {
        $domain = parse_url($link, PHP_URL_HOST);
        return !empty($domain);
    }

    protected function isLinkExternal($link)
    {
        $parent_domain = parse_url($this->link, PHP_URL_HOST);
        $link_domain = parse_url($link, PHP_URL_HOST);

        return !empty($link_domain) && $parent_domain != $link_domain;
    }

    protected function getFullLink($link)
    {
        if ($this->isLinkExternal($link)) {
            return;
        }

        if ($this->isLinkFull($link)) {
            return $link;
        }

        if (empty($this->prefix)) {
            $parts = parse_url($this->link);
            $path = @$parts['path'];
            $path = explode('/', $path);
            array_pop($path);
            $path = implode('/', $path);

            if ($path[0] == '/') {
                $path = '';
            }
            $this->prefix = "{$parts['scheme']}://{$parts['host']}{$path}";


        }

        $link = ltrim($link, '/');
        $full = "{$this->prefix}/{$link}";
//        print_r([$host , $path, $link, $full ]); die;
        return $full;
    }

    public function getLinks()
    {
        $contents = $this->getContents();

        $crawler = new DomCrawler\Crawler($contents);

        $links = $crawler->filter('a')->each(function (DomCrawler\Crawler $node) {
            return $node->attr('href');
        });

        $links = array_map([$this, 'getFullLink'], $links);
        $links = array_filter(array_unique($links));

        return $links;
    }
}