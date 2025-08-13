<?php
spl_autoload_register(
    function ( $class ) {
        if ( strpos( $class, 'AiWpSeoCheck\\' ) !== 0 ) {
            return;
        }
        $path = __DIR__ . '/../src/' . str_replace( '\\', '/', substr( $class, strlen( 'AiWpSeoCheck\\' ) ) ) . '.php';
        if ( file_exists( $path ) ) {
            require $path;
        }
    }
);
