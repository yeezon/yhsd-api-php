<?php
require_once 'DemoTest.php';


$method = isset($_GET['method']) ? $_GET['method'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$app = new DemoTest($type);

$func = 'test_' . trim($method);
$data = $app->$func();
header("Content-type: text/html; charset=utf-8");

echo '<a href="index.php">返回</a>';


echo "<hr/>"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>友好速搭Demo</title>
</head>
<body>
<h2>运行结果</h2>
<hr/>
<pre>
<?php var_export($data) ?>
</pre>

<a href="index.php">返回</a>

</body>
</html>
