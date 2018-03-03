<?php

stream_set_blocking(STDIN, false);

class TapTempo {

    private static $taps = [];
    private static $interval = 60;
    private static $lastInterval;
    private static $intervals;
    private static $bpm;

    public static function tap() {
        array_push(self::$taps, microtime(true));
        self::getBpm() && self::printResult();
    }

    public static function greet() {
        echo "Welcome to this PHP implementation of TapTempo.\n\n";
        echo "Press <Enter> to start.\n";
        echo "Press <Ctrl-C> to quit.\n";
    }

    private static function printResult(){
        echo "BPM : ".self::$bpm;
        echo "\n";
    }

    private static function getBpm():bool {
        $c = count(self::$taps);

        if ($c === 1) {
            echo "First Tap !";
            return false;
        }elseif ($c > 5) {
            array_shift(self::$taps);
        }

        self::lastInterval();

        array_map(function(float $e){
            array_push(self::$intervals, $e - self::$lastInterval);
        }, self::$taps);

        $mul = self::$interval / array_sum(self::$intervals);
        self::$bpm = abs(count(self::$intervals) * $mul * 2); 

        return true;
    }

    private static function lastInterval() {
        end(self::$taps);
        self::$lastInterval = current(self::$taps);
        reset(self::$taps);
        self::$intervals = [];
    }

}


TapTempo::greet();

while (true) {
    if (fgetc(STDIN) !== false) TapTempo::tap();
}


