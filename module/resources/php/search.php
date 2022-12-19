<?php

/**
 * @var Navigator $this - current init
 */

date_default_timezone_set('Asia/Jakarta');
require_once __TOOLS__;
require_once __DATABASE__;

use Module\Classes\VM;

if (isset($_GET['query']) && $_GET['query'] != "") {

  $query = filter_input(INPUT_GET, 'query', FILTER_UNSAFE_RAW);

  $perpage = 15;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $starting_limit = ($page - 1) * $perpage;

  $_SQL = "SELECT SQL_CALC_FOUND_ROWS *,
            ( (1 * (MATCH(title) AGAINST (? IN BOOLEAN MODE))) + (0.1 * (MATCH(description) AGAINST (? IN BOOLEAN MODE)))) AS relevance
            FROM masterpage 
            WHERE (MATCH(title,description) AGAINST (? IN BOOLEAN MODE) )
            GROUP BY title
            ORDER BY relevance DESC LIMIT $starting_limit,$perpage";

  $stmt = $DBH->prepare($_SQL);
  $stmt->execute([$query, $query, $query]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($result) <= 0) {

    $SQL = "SELECT SQL_CALC_FOUND_ROWS * FROM masterpage WHERE title LIKE ? OR description LIKE ? LIMIT $starting_limit,$perpage";

    $stmt = $DBH->prepare($SQL);
    $stmt->execute([ "%$query%", "%$query%"]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  }

  $count = $DBH->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);

  $total_pages =  array_key_exists('0', $result) ? ceil($count / $perpage) : 0;
  $totalresult = array_key_exists('0', $result) ? intval($count) : 0;

  $visitorip = tools::getVisitorIp();
  $document->html = str_replace('{VISITOR_IP}', $visitorip, $document->html);
  $document->html = str_replace('{TEXT_QUERY}', $query, $document->html);
  $document->html = str_replace('{TOTAL_LENGTH}', $totalresult, $document->html);

  $DOM = "<h4>Pak Gatoot tidak dapat menemukan $query</h4><br/><a href='/website/'>Bantuin pak Gatoot</a>";

  if ($result) {

    $DOM = "<div>"; //json_encode($result);

    foreach ($result as $sites) {

      $host = $sites['host'];
      $url = $sites['url'];
      $title = $sites['title'];
      $description = $sites['description'];

      $parse = parse_url($url);
      $bucket = "<span class='text-secondary'>";

      if (array_key_exists('path', $parse)) {
        $path = explode('/', $parse['path']);

        $path = array_values(array_filter($path, function ($val) {
          return $val != "";
        }));

        $pathKey = array_keys($path);

        foreach ($pathKey as $key) {
          $pval = $path[$key];

          if ($key < 2) {
            if (preg_match('~[0-9]+~', $pval)) {
              $bucket .= " &#8250; ...";
            } else {
              if (strlen($pval) > 12) {
                $pval = "...";
              }
              $bucket .= " &#8250; " . $pval;
            }
          }
        }
      }
      $bucket .= "</span>";

      $_url = $parse['scheme'] . "://" . $parse['host'];

      $DOM .= "<div class='bg-light p-1'>";
      $DOM .= "<a href='$url'>";
      $DOM .= "<small class='text-dark'>$_url $bucket</small>";
      $DOM .= "<br />";
      $DOM .= "<span class='text-primary font-larger' style='line-height: 0.8em;white-space: pre-line;'>$title</span>";

      $DOM .= "</a>";

      $DOM .= "<p class='mt-2 font-smaller'>$description</p>";


      $DOM .= "</div><hr />";
    }

    $DOM .= "</div>";



    $DOM .= '<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">';

    if ($total_pages > 1) {
      if ($page > 1) {
        $DOM .= '<li class="page-item">
                    <a class="page-link" href="?query=' . urlencode($query) . '&page=' . ceil($page - 1) . '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>';
      }
      $pagesElement = "";

      $maxTabs = 6;
      $maxOfset = 3;
      $counter = $page > 1 ? ceil($page - $maxOfset) : 0;

      if ($page > $maxOfset) {
        $pagesElement .= "<li class='page-item mr-3'><a class='page-link' href='?query=$query&page=1'>1</a></li>";
      }
      for ($i = 0; $i < $maxTabs; $i++) {
        if ($counter < $total_pages) {
          $counter++;
          $link = $page == $counter ? "" : 'href=?query=' . urlencode($query) . '&page=' . $counter;
          $active = $page == $counter ? " active" : '';
          if ($counter > 0) {

            $pagesElement .= '<li class="page-item' . $active . '"><a class="page-link" ' . $link . '>' . $counter . '</a></li>';
          }
        }
      }

      $DOM .= $pagesElement;

      if ($page < $total_pages) {

        $DOM .= '<li class="page-item">
                    <a class="page-link" href="?query=' . urlencode($query) . '&page=' . ceil($page + 1) . '" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>';
      }
    }

    $DOM .= '</ul>
</nav>';
  }

  $document->html = str_replace('[RESULT]', $DOM, $document->html);
} else {

  header("Location: /");
}
