<?php

require 'vendor/autoload.php';

use UAParser\Parser;

$parser = Parser::create();
$result = $parser->parse($_SERVER['HTTP_USER_AGENT']);

// Retourneer de browserinformatie als een string
$browserInfo = $result->ua->family . ' ' . $result->ua->major . '.' . $result->ua->minor;

return $browserInfo;
