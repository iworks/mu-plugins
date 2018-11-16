<?php

if ( !function_exists('d') ) {
    function d( $a, $b = '' )
    {
        if (!is_array($a) ) {
            $a= (array) $a;
        }
        echo '<pre>';
        echo $b;
        print_r( $a );
        echo '</pre>';
    }
}
