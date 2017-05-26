<?php
function printr($data){
    print_r($data);
    echo "<br />";
}


$singer = array(
    0 => 'adele',
    1 => 'taylor swift',
    2 => 'kobe bryant',
    3 => 'ronaldinho'
);
array_walk($singer, function(&$value, $key)use($singer){
    if($key == 0){
        $value = $value . ' adkins';
    }
});
printr($singer);


$filter_arr = array(
    0 => 1,
    1 => 2,
    2 => 3,
    3 => 4,
    4 => 1.5
);
$filtered_arr = array_filter($filter_arr, function($value)use($filter_arr){
    if(is_int($value)){
        return true;
    }
    return false;
});
printr($filtered_arr);


$map_arr = array(
    0 => 'set fire in the rain',
    1 => 'someone like you',
    2 => 'dont you remember',
    3 => 'rolling in the deep',
    4 => 'skyfall',
    5 => 'untouchable',
    6 => 'you belong with me',
    7 => 'I stay in love',
    8 => 'last kiss',
    9 => 'demo'
);
$map_arr_new = array_map(function($value)use($map_arr){
    if(strlen($value) >= 5){
        return $value;
    }
    return 'we belong together';
}, $map_arr);
printr($map_arr_new);


$phone_arr = array(
    0 => '15822442528',
    1 => '13485704592',
    2 => '13693239317',
    3 => '15155956872'
);
$phone_arr_new = preg_replace_callback('/(\d{4})(\d{4})(\d{3})/', function($matches)use($phone_arr){
    return sprintf("%s****%s", $matches[1], $matches[3]);
}, $phone_arr);
printr($phone_arr_new);


