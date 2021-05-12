<?php

require __DIR__ . '/vendor/autoload.php';
$client = new \Instalogin\Client('VluObzy1BoNiFcgm5OXSQun42pF9pFNx', '7e2672af946831902319d3a17573bac3ed3897be1eb3c962b074bd62e75293cb');

$headers = getallheaders();

echo "<pre>";
print_r($headers);
echo "</pre>";

echo "<pre>";
print_r($_SERVER);
echo "</pre>";

try {
    $jwt = mb_substr($headers['Authorization'], 7);
    $token = $client->decodeJwt($jwt);

    // TODO: Check username
    
    if (!$client->verifyToken($token)) {
        return false;
    }

    // TODO set auth headers for wp

    return true;
} catch (\Throwable $th) {
    return false;
}
