<?php
header('Content-Type: application/json; charset=utf-8');
function jsonEncode($array) {
    $json = '';
    $first = true;
    foreach($array as $key => $value) {
        if(!$first) {
            $json .= ',';
        }
        $json .= '"' . $key . '":';
        if(is_array($value)) {
            $json .= jsonEncode($value);
        } else {
            $json .= '"' . $value . '"';
        }
        $first = false;
    }
    $json = '{' . $json . '}';
    return $json;
}

$arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
$productos = array(	
	
	'Bolígrafo Azul' => array(
		'marca' => "Bic",
		'precio'  => "0.75€",
		'referencia'  => "552BIC12"
	),
	
	'Pegamento' => array(
		'marca' => "Pritt",
		'precio'  => "1.75€",
		'referencia'  => "567PRI13"
	)
);

echo array2json($productos)

?>