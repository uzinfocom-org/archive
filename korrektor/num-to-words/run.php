<?php

/**
 * @link	https://korrektor.uz
 * @link	https://github.com/korrektoruz/num-to-words
*/

function numToWords($number=0) {
    
    $number = abs((int)$number);
    
    if ( $number >= PHP_INT_MAX ) return FALSE;

    $_1to19 = [ 'bir', 'ikki', 'uch', 'to‘rt', 'besh', 'olti', 'yetti', 'sakkiz', 'to‘qqiz', 'o‘n', 'o‘n bir', 'o‘n ikki', 'o‘n uch', 'o‘n to‘rt', 'o‘n besh', 'o‘n olti', 'o‘n yetti', 'o‘n sakkiz', 'o‘n to‘qqiz'
    ];
    
    $_teen = [ 'yigirma', 'o‘ttiz', 'qirq', 'ellik', 'oltmish', 'yetmish', 'sakson', 'to‘qson' ];
    
    $_mult = [
        2  => 'yuz',
        3  => 'ming',
        6  => 'million',
        9  => 'milliard',
        12 => 'trillion',
        15 => 'kvadrillion',
        18 => 'kvintilion',
        21 => 'sekstilion',
        24 => 'septilion', 
        27 => 'oktilion',
    ];
    $fnBase = function ( $n, $x ) use ( &$fn, $_mult ) {
        return $fn( $n / ( 10 ** $x ) ) . ' ' . $_mult[ $x ];
    };
    $fnOne = function ( $n, $x ) use ( &$fn, &$fnBase ) {
        $y = ( (int)$n % ( 10 ** $x ) ) % ( 10 ** $x );
        $s = $fn( $y );
        $sep = ( $x === 2 && $s ? " " : ( $y < 100 ? ( $y ? " " : '' ) : ' ') );
        return $fnBase( $n, $x ) . $sep . $s;
    };
    $fnHundred = function ( $n, $x ) use ( &$fn, &$fnBase ) {
        $y = $n % ( 10 ** $x );
        $sep = ( $y < 100 ? ( $y ? ' ' : '' ) : ' ' );
        return ', ' . $fnBase( $n, $x ) . $sep . $fn( $y );
    };
    $fn = function ( $n ) use ( &$fn, $_1to19, $_teen, $number, &$fnOne, &$fnHundred ) {
        switch ( $n ) {
            case 0:
                return ( $number > 1 ? '' : 'nol' );
            case $n < 20:
                return $_1to19[ (int)($n - 1) ];
            case $n < 100:
                return $_teen[ (int) ( (int)$n / 10 ) - 2 ] . ' ' . $fn( (int)$n % 10 );
            case $n < ( 10 ** 3 ):
                return $fnOne( $n, 2 );
        };
        for ( $i = 4; $i < 27; ++$i ) {
            if ( $n < ( 10 ** $i ) ) {
                break;
            }
        }
        return ( $i % 3 ) ? $fnHundred( $n, $i - ( $i % 3 ) ) : $fnOne( $n, $i - 3 );
    };
    $number = $fn( (int)$number );
    $number = str_replace( ', , ', ', ', $number );
    $number = str_replace( ',  ', ', ', $number );
    $number = str_replace( '  ', ' ', $number );
    $number = ltrim( $number, ', ' );
    return $number;
}

echo numToWords('123');
