<?php
class FP
{
    public static function every($function, $array) : bool {
        for ($i = 0; $i < count($array); $i++) {
            if (!$function($array[$i])) return false;
        }
        return true;
    }
}
