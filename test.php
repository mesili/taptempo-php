<?php

stream_set_blocking(STDIN, false);

class TapTempo {

    private static $history=[];
    private static $interval = 60;
    private static $lastInterval;
    private static $intervals;
    private static $bpm;

    public static function tap(bool $key) {
        array_push(self::$history, microtime(true));
        self::getBpm() && self::printResult();
    }

    private static function printResult(){
        echo "BPM : ".self::$bpm;
        echo "\n";
    }

    private static function getBpm():bool {
        $c = count(self::$history);

        if ($c === 1) {
            echo "First Tap !";
            return false;
        }
        if ($c > 5) array_shift(self::$history);

        self::lastInterval();

        array_map(function(float $e){
            array_push(self::$intervals, $e - self::$lastInterval);
        }, self::$history);

        $mul = self::$interval / array_sum(self::$intervals);
        self::$bpm = abs( count(self::$intervals) * $mul * 2); 

        return true;
    }

    private static function lastInterval() {
        end(self::$history);
        self::$lastInterval = current(self::$history);
        reset(self::$history);
        self::$intervals = [];
    }

}

while (true) {
    if (fgetc(STDIN) !== false) TapTempo::tap(fgetc(STDIN));
}
