<?php
error_reporting(E_ALL);
$filename = 'myfile4.txt';
/*if (is_writable($filename)) {
    echo 'The file is writable';
} else {
    echo 'The file is not writable';
} */

$file = fopen($filename,"w")or die("last error".get_last_error());
chmod($filename, 0777);
echo fwrite($file,"Hello World. Sudhir Testing!");
fclose($file);

print_r(error_get_last());
//shell_exec("python simple.py");
?>