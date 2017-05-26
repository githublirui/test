<?php
$path = $GLOBALS['now_path'] . '/regpre.jpg';
$path1 = $GLOBALS['now_path'] . '/regpre1.jpg';

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb");
    $data = explode(',', $base64_string);
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
    return $output_file;
}

$image = base64_to_jpeg($base64, $path1);
?>
<img src="<?php echo $base64; ?>" />