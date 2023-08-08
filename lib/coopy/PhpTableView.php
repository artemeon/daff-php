<?php

namespace coopy;

class PhpTableView implements Table {
    /**
     * @var array[]
     */
    public $data;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $width;

    public function __construct ($data) {
        $this->data = $data;
        $this->height = count($data);
        $this->width = 0;
        if ($this->height > 0) {
            $this->width = count($data[0]);
        }
    }

    /**
     * @return int
     */
    public function get_width () {
        return $this->width;
    }

    /**
     * @return int
     */
    public function get_height () {
        return $this->height;
    }

    /**
     * @param int $x
     * @param int $y
     * @return mixed
     */
    public function getCell ($x, $y) {
        return $this->data[$y][$x];
    }

    /**
     * @param int $x
     * @param int $y
     * @param mixed $c
     * @return void
     */
    public function setCell ($x, $y, $c) {
        $this->data[$y][$x] = \Std::string($c);
    }

    /**
     * @return string
     */
    public function toString () {
        return SimpleTable::tableToString($this);
    }

    /**
     * @return PhpCellView
     */
    public function getCellView () {
        return new PhpCellView();
    }

    /**
     * @return bool
     */
    public function isResizable () {
        return true;
    }

    /**
     * @param int $w
     * @param int $h
     * @return bool
     */
    public function resize ($w, $h) {
        $this->width = $w;
        $this->height = $h;
        foreach ($this->data as $i => $iValue) {
            $row = &$this->data[$i];
            if ($row === null) {
                $this->data[$i] = [];
                $row = &$this->data[$i];
            }
            while (count($row) < $this->width) {
                $row[] = null;
            }
            unset($row);
        }
        if (count($this->data) < $this->height) {
            $start = count($this->data);
            for ($i = $start; $i < $this->height; $i++) {
                $row = array_pad([], $this->width, null);
                $this->data[] = $row;
                unset($row);
            }
        }
        return true;
    }

    /**
     * @return void
     */
    public function clear () {
        foreach ($this->data as $i => $iValue) {
            $row = &$this->data[$i];
            foreach ($row as $j => $jValue) {
                $row[$j] = null;
            }
        }
    }

    /**
     * @return bool
     */
    public function trimBlank () {
        return false;
    }

    /**
     * @return array[]
     */
    public function getData () {
        return $this->data;
    }

    /**
     * @param \Array_hx|int[] $xfate
     * @param int $hfate
     * @return bool
     */
    public function insertOrDeleteRows ($xfate, $hfate) {
        if (is_array($xfate)) {
            $fate = $xfate;
        } else {
            $fate = $xfate->arr;
        }
        $ndata = [];
        $top = 0;
        for ($i = 0, $iMax = count($fate); $i < $iMax; $i++) {
            $j = $fate[$i];
            if ($j !== -1) {
                for ($k = count($ndata); $k < $j; $k++) {
                    $ndata[$k] = null;
                }
                $ndata[$j] = &$this->data[$i];
                if ($j > $top) {
                    $top = $j;
                }
            }
        }
        // let's preserve data
        array_splice($this->data, 0, count($this->data));
        for ($i = 0; $i <= $top; $i++) {
            $this->data[$i] = &$ndata[$i];
        }
        $this->resize($this->width, $hfate);
        return true;
    }

    /**
     * @param \Array_hx|int[] $xfate
     * @param int $wfate
     * @return bool
     */
    public function insertOrDeleteColumns ($xfate, $wfate) {
        if (is_array($xfate)) {
            $fate = $xfate;
        } else {
            $fate = $xfate->arr;
        }
        if ($wfate === $this->width && $wfate === count($fate)) {
            $eq = true;
            for ($i = 0; $i < $wfate; $i++) {
                if ($fate[$i] !== $i) {
                    $eq = false;
                    break;
                }
            }
            if ($eq) {
                return true;
            }
        }
        $touched = [];
        $top = 0;
        for ($j = 0; $j < $this->width; $j++) {
            if ($fate[$j] === -1) {
                continue;
            }
            $touched[$fate[$j]] = 1;
            if ($fate[$j] > $top) {
                $top = $fate[$j];
            }
        }
        for ($i = 0; $i < $this->height; $i++) {
            $row = &$this->data[$i];
            $nrow = [];
            for ($j = 0; $j < $this->width; $j++) {
                $fj = $fate[$j];
                if ($fj === -1) {
                    continue;
                }
                for ($k = count($nrow); $k < $fj; $k++) {
                    $nrow[$k] = null;
                }
                $nrow[$fj] = $row[$j];
            }
            for ($j = $top; $j < $wfate-1; $j++) {
                $nrow[] = null;
            }
            $this->data[$i] = $nrow;
        }
        $this->width = $wfate;
        for ($j = 0; $j < $this->width; $j++) {
            if (!isset($touched[$j])) {
                for ($i = 0; $i < $this->height; $i++) {
                    $this->data[$i][$j] = null;
                }
            }
        }
        //if ($this->width === 0) $this->height = 0;
        return true;
    }

    /**
     * @param static $alt
     * @return bool
     */
    public function isSimilar ($alt) {
        if ($alt->width !== $this->width) {
            return false;
        }
        if ($alt->height !== $this->height) {
            return false;
        }
        for ($c = 0; $c < $this->width; $c++) {
            for ($r = 0; $r < $this->height; $r++) {
                $v1 = "" . $this->getCell($c, $r);
                $v2 = "" . $alt->getCell($c, $r);
                if ($v1 !== $v2) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @return static
     */
    public function clone () {
        $blank = [];
        $result = new static($blank);
        $result->resize($this->width, $this->height);
        for ($c = 0; $c < $this->width; $c++) {
            for ($r = 0; $r < $this->height; $r++) {
                $result->setCell($c, $r, $this->getCell($c, $r));
            }
        }
        return $result;
    }

    /**
     * @return static
     */
    public function create () {
        $blank = [];
        return new static($blank);
    }

    /**
     * @return null
     */
    public function getMeta () {
        return null;
    }
}
