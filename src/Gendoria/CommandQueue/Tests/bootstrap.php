<?php

call_user_func(function() {
    $autoloadFile = __DIR__ . '/../../../../vendor/autoload.php';
    if (!is_file($autoloadFile)) {
        throw new LogicException('Could not find vendor/autoload.php. Did you forget to run "composer install --dev"?');
    }
    require $autoloadFile;
});