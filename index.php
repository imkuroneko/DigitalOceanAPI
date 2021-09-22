<?php
require './do.class.php';

$key = 'tu_token_aqui';

$do = new DigitalOcean();
$digitalocean = $do->connect($key);

echo "<pre>";
var_dump($do->createDroplet('nombre', 'nyc3', 's-1vcpu-2gb', 'ubuntu-20-04-x64'));
?>
