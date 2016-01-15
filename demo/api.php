<?php
$_PUT = array();
$str = file_get_contents('php://input');
$str = json_decode($str,true);
echo json_encode(['body'=>$str]);
//apache_response_headers();