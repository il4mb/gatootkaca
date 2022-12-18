<?php
date_default_timezone_set('Asia/Jakarta');

class submitSite
{
    public $url, $host, $title, $description;
    public $database, $illegal = ['}', '{'];

    function __construct(array $data)
    {

        $url = parse_url($data['url']);

        $urlstr = $data['url'];
        $host = $url['host'];
        $title = $data['title'];
        $description = $data['description'];

        $this->url = filter_var($urlstr, FILTER_VALIDATE_URL);
        $this->host = filter_var($host, FILTER_UNSAFE_RAW);
        $this->title = filter_var($title, FILTER_UNSAFE_RAW);
        $this->description = filter_var($description, FILTER_UNSAFE_RAW);

    }

    public function init()
    {
        if (strlen($this->description) > 15 && !$this->is_illegal_str($this->description)) {

            if ($this->url && $this->host && $this->title && $this->description) {
                if ($this->url != "" && $this->host != "" && $this->title != "" && $this->description != "") {

                    $SQL = "SELECT date FROM masterpage WHERE host=? AND url=?";
                    $stmt = $this->database->prepare($SQL);
                    $stmt->execute([$this->host, $this->url]);
                    $result  = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result) {

                        $today_dec3day = date('Y-m-d H:i:s', strtotime('-3 day'));
                        $lastupdate = date('Y-m-d H:i:s', strtotime($result['date']));

                        if ($lastupdate < $today_dec3day) {

                            $this->update();
                        }
                    } else {

                        $this->insert();
                    }
                }
            }
        }
    }

    private function insert()
    {

        $SQL = "INSERT INTO `masterpage` (`host`, `url`, `title`, `description`, `date`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->database->prepare($SQL);
        $stmt->execute([$this->host, $this->url, $this->title, $this->description, date('Y-m-d H:i:s')]);
    }
    private function update()
    {

        $SQL = "UPDATE `masterpage` SET title=?, description=?, date=? WHERE url=?";
        $stmt = $this->database->prepare($SQL);
        $stmt->execute([$this->title, $this->description, date('Y-m-d H:i:s'), $this->url]);
    }

    public function is_illegal_str($str)
    {

        $illegal = false;

        foreach ($this->illegal as $exception) {
            if ($exception != "") {
                if (str_contains($str, $exception)) {

                    $illegal = true;
                }
            }
        }
        
        if (strlen(trim($str)) == 0) {
            $illegal = true;
        }

        return $illegal;
    }
}
