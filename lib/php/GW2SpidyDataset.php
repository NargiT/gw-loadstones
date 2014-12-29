<?php

ini_set('max_execution_time', 0);

class GW2SpidyDataset {

// Data interest us
    private $ids ;
    protected $data = array();

    public function __construct($dataset) {
        $this->ids = $dataset;
    }
    public function showData() {
        if (!$this->data) {
            $this->getData();
        }
        var_dump($this->data);
    }

    public function getItem($name) {
        if (!$this->data) {
            $this->getData();
        }

        $toReturn = null;
        foreach ($this->data as $array) {
            if ($array['name'] == $name) {
                $toReturn = $array;
                break;
            }
        }
        return $toReturn;
    }

    protected function getData() {
        for ($i = 0; $i < count($this->ids); $i++) {
            $tmp = json_decode(file_get_contents("http://www.gw2spidy.com/api/v0.9/json/item/" . $this->ids[$i]), true);
            $this->data[$i] = $tmp['result'];
        }
    }

}

function annonce($value) {
    return (int) (5 * $value / 100);
}

function taxe($value) {
    return (int) (10 * $value / 100);
}

?>
