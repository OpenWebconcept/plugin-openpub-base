<?php
require(__DIR__ ."/../../vendor/autoload.php");
$openapi = \OpenApi\scan(__DIR__.'/../../src/Base');
header('Content-Type: application/x-yaml');
echo preg_replace('/x-description/i', 'description', $openapi->toYaml());
