<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/module/define.php";
require_once __TOOLS__;

use Goutte\Client;

$A = new CRAWLER();

$A->start();


class CRAWLER
{
    public $url, $limit = 10, $deep = 1, $strict = true, $sameorigin = true;
    private $client, $crawler, $host, $scheme;
    public $crawled = [], $database = null;

    function __construct()
    {
        $this->client = new Client();
        $this->client->setServerParameter('User-Agent', "GatootBoot/0.54");
        $this->client->setServerParameter('HTTP_USER_AGENT', 'GatootBoot/0.54');
    }

    function init()
    {

        $parse = parse_url($this->url);
        $this->scheme = array_key_exists('scheme', $parse) ? $parse['scheme'] : "http";
        $this->host = $parse['host'];
    }

    function start($deep = 0)
    {
        $url = $this->clean_url($this->url, $this->strict, $this->sameorigin);

        return $this->fetch($url, $deep);
    }

    private function fetch($url, $deep = 0)
    {
        $title = "";
        $icon = "";
        $description = "";

        if ($this->limit > count($this->crawled)) {

            if ($deep >= 0 && !$this->is_crawled($url)) {

                try {
                    $this->crawler = $this->client->request('GET', $url);

                    if ($this->client->getResponse()->getStatusCode() == 200) {

                        $title = $this->crawler->filterXPath('//title')->text();
                        $title = $this->init_text($title, 85);

                        $description = $this->crawler->filter('meta')->each(function ($node) {

                            $name_description = $node->attr('name');
                            if ($name_description && 0 == strcmp('description', $name_description)) {
                                $content = $node->attr('content');
                                if(strlen($content) > 0) {
                                    return $content;
                                }
                                
                            }

                            $name_description = $node->attr('property');
                            if ($name_description && 0 == strcmp('og:description', $name_description)) {
                                $content = $node->attr('content');
                                if(strlen($content) > 0) {
                                    return $content;
                                }
                            }
                        });
                        $description = array_key_exists('0', $description) ? $description[0] : "";
                        $description = ltrim($description);
                        
                        if (strlen($description) < 1) {
                            $description = self::getTextBody($this->crawler);
                        }
                        $description = $this->init_text($description, 165);

                        $icon = $this->crawler->filter('link')->each(function ($node) {

                            $rel = $node->attr('rel');
                            if ($rel && $rel != "" && str_contains($rel, "icon")) {

                                return $node->attr('href');
                            }
                        });
                        $icon = array_values(array_filter($icon, function ($val) {
                            return $val && $val != "";
                        }));

                        if (array_key_exists('0', $icon))
                            $icon = self::clean_url($icon[0], false, false);
                        else
                            $icon = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23949494' width='15' height='15' viewBox='0 0 16 16' transform='rotate(25)'%3E%3Cpath d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z'/%3E%3C/svg%3E";

                        $dataFromGrabed = [
                            "url" => $url,
                            "icon" => $icon,
                            "title" => $title,
                            "description" => $description
                        ];

                        $this->crawled[count($this->crawled)] = $dataFromGrabed;

                        /**
                         * SUBMIT CRAWLED
                         */
                        if ($this->database != null) {

                            @$this->submit($dataFromGrabed);
                        }

                        $this->crawler->filter('a')->each(function ($node) use ($deep) {


                            $rel = $node->attr('rel');

                            if (0 !== strcmp('nofollow', $rel)) {

                                $attr = $node->attr('href');
                                $url = $this->clean_url($attr, $this->strict, $this->sameorigin);

                                if ($url && filter_var($url, FILTER_VALIDATE_URL)) {

                                    $rel = $node->attr('rel');

                                    if (0 !== strcmp('nofollow', $rel)) {
                                        $deep--;
                                        $this->fetch($url, $deep);
                                    }
                                }
                            }
                        });
                    } // else echo $this->client->getResponse()->getStatusCode() . " ";
                } catch (Exception $ex) {

                    // echo $ex;

                }
            }

            return $this->crawled;

        } else return;
    }

    private function submit($data)
    {
        try {

            $_url = parse_url($data['url']);

            $url = filter_var($data['url'], FILTER_VALIDATE_URL);
            $host = filter_var($_url['host'], FILTER_UNSAFE_RAW);
            $title = filter_var($data['title'], FILTER_UNSAFE_RAW);
            $description = filter_var($data['description'], FILTER_UNSAFE_RAW);

            $submit = new submitSite([
                "host" => $host,
                "url" => $url,
                "title" => $title,
                "description" => $description
            ]);
            $submit->database = $this->database;
            $submit->init();
        } catch (Exception $ex) {
        }
    }

    private function is_crawled($url)
    {

        $exist = array_filter($this->crawled, function ($val) use ($url) {
            return 0 == strcmp($val['url'], $url);
        });

        return count($exist) > 0;
    }

    public function clean_url($url, $strict = true, $sameorigin = true)
    {

        $parse = parse_url($url);
        if ($parse) {
            if (!array_key_exists('host', $parse)) {

                $parse['host'] = $this->host;
            } else if ($sameorigin) {

                if (0 !== strcmp(strtolower($parse['host']), strtolower($this->host))) {
                    return false;
                }
            }

            $query = "";
            if (!array_key_exists('scheme', $parse)) {

                $parse['scheme'] = $this->scheme;
            }

            $path = array_key_exists('path', $parse) ? $parse['path'] : "";
            $path = explode("/", $path);

            $path = array_filter($path, function ($val) {
                return $val != "";
            });

            if (count($path) > 0) {
                $path = "/" . implode("/", $path);
            } else {
                $path = "";
            }

            if (!$strict) {

                if (array_key_exists('query', $parse)) {
                    $query = "?" . $parse['query'];
                }
            }

            $url = $parse['scheme'] . "://" . $parse['host'] . $path . $query;
            return $url;
        }
    }

    private function robots()
    {
        $path = $this->host . "/robots.txt";
        $this->crawler = $this->client->request('GET', $path);
    }
    private static function getTextBody($crawler)
    {
        $description = "";

        $h1 = @$crawler->filter('h1')->each(function ($node, $i) {
            return $node->text();
        });
        $h2 = @$crawler->filter('h2')->each(function ($node, $i) {
            return $node->text();
        });
        $h3 = @$crawler->filter('h3')->each(function ($node, $i) {
            return $node->text();
        });
        $h4 = @$crawler->filter('h4')->each(function ($node, $i) {
            return $node->text();
        });
        $h5 = @$crawler->filter('h5')->each(function ($node, $i) {
            return $node->text();
        });
        $h6 = @$crawler->filter('h6')->each(function ($node, $i) {
            return $node->text();
        });
        $th = @$crawler->filter('th')->each(function ($node, $i) {
            return $node->text();
        });
        $td = @$crawler->filter('td')->each(function ($node, $i) {
            return $node->text();
        });
        $div = @$crawler->filter('div')->each(function ($node, $i) {
            return $node->text();
        });
        $p = @$crawler->filter('p')->each(function ($node, $i) {
            return $node->text();
        });
        $nodeText = [
            implode(" ", $h1),
            implode(" ", $h2),
            implode(" ", $h3),
            implode(" ", $h4),
            implode(" ", $h5),
            implode(" ", $h6),
            implode(" ", $th),
            implode(" ", $td),
            implode(" ", $div),
            implode(" ", $p)
        ];

        $description = implode(" ", $nodeText);

        return strip_tags($description);
    }

    public static function init_text($text, $len)
    {

        $text = filter_var(strip_tags($text), FILTER_UNSAFE_RAW);

        if (strlen($text) > $len && $len > 50) {

            $text = substr($text, 0, ceil($len - 3)) . "...";
        }
        
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = htmlspecialchars($text);

        $text = ltrim($text);
        $text = preg_replace('/\s\s+/', '', $text);

        return $text;
    }
}
