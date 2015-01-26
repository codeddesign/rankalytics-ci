<?php
error_reporting( 0 );
$file_name = "";
$result    = 0;
if (isset( $_POST['website'] ) && $_POST['website']) {
    $url = addhttp( trim( $_POST['website'] ) );
    $url = preg_replace( '#^www\.(.+\.)#i', '$1', $url );

    $keyword       = urlencode( $_POST["keyword"] );
    $keyword_array = array();
    $keyword_array = explode( "+", $keyword );
    $count         = 0;
    $sum           = 0;
    $file_exists   = false;
    foreach ($keyword_array as $value) {
        $value       = trim( $value );
        $file_name   = md5( $site_name . $value . date( "Y-m-d H:i:s" ) ) . ".txt";
        $current_dir = realpath( __DIR__ );
        $save_dir    = realpath( __DIR__ . '/../' );

        $exec_path = "/usr/bin/python " . $current_dir . "/similarity.py --domain {$url} --keyword {$value} --output " . $save_dir . "/similarities/{$file_name}";
        $response  = shell_exec( $exec_path );

        $file      = $save_dir . "/similarities/" . $file_name;
        $file_temp = $file;

        if (file_exists( $file )) {
            $file_exists = true;
            $file        = fopen( $file, "r" );
            while ( ! feof( $file )) {
                $string = fgets( $file );
                if (stristr( $string, $value ) !== false) {
                    $temp_array = explode( "+", urlencode( $string ) );
                    if (strcasecmp( $temp_array[0], $value ) == 0) {
                        $count ++;
                        $sum = $sum + $temp_array[2];
                    }
                }
            }
            @unlink( $file_temp );
        }

    }
    if ($count > 0) {
        $result = $sum / $count;
    } else {
        $result = 0;
    }

    if ( ! $file_exists) {
        echo json_encode( array( "similarity_score" => ( $result ) ? $result : "N/A" ) );
    } else {
        echo json_encode( array( "similarity_score" => ( $result ) ? ( $result * 100 ) . "%" : "0%" ) );
    }

}
function addhttp( $url )
{
    if ( ! preg_match( "~^(?:f|ht)tps?://~i", $url )) {
        $url = "http://" . $url;
    }
    return $url;
}

exit;
?>
