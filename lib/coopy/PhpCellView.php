<?php

namespace coopy;

use haxe\ds\StringMap;
use haxe\IMap;

class PhpCellView implements View {
    /**
     * @param mixed|null $d1
     * @param mixed|null $d2
     * @return bool
     */
    public function equals ($d1, $d2) {
        if ($d1 === null && $d2 === null) {
            return true;
        }
        if ($d1 === null || $d2 === null) {
            return false;
        }
        return ("" . \Std::string($d1)) === ("" . \Std::string($d2));
    }

    /**
     * @param mixed $str
     * @return mixed
     */
    public function toDatum ($str) {
        return $str;
    }

    /**
     * @return IMap
     */
    public function makeHash () {
        return new StringMap();
    }

    /**
     * @param mixed $d
     * @return bool
     */
    public function isHash ($h) {
        return ($h instanceof StringMap);
    }

    /**
     * @param mixed $h
     * @param string $str
     * @return bool
     */
    public function hashExists ($h, $str) {
        if (!($h instanceof StringMap)) {
            return false;
        }
        return ($h->data[$str] ?? null);
    }

    public function hashGet ($h, $str) {
        if (!($h instanceof StringMap)) {
            return false;
        }
        return $h->data[$str] ?? null;
    }

    /**
     * @param mixed $h
     * @param string $str
     * @param mixed $d
     * @return void
     */
    public function hashSet ($h, $str, $d) {
        if (!($h instanceof StringMap)) {
            return;
        }
        $h->data[$str] = $d;
    }

    /**
     * @param mixed $t
     * @return bool
     */
    public function isTable ($t) {
        return false;
    }

    /**
     * @param mixed $t
     * @return bool
     */
    public function getTable ($t) {
        return false;
    }

    /**
     * @param mixed $t
     * @return bool
     */
    public function wrapTable ($t) {
        return false;
    }

    public function toString ($d) {
        if ($d === null) {
            return "";
        }
        return "" . \Std::string($d);
    }
}
