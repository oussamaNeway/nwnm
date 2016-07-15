<?php

$envVar = getenv('PHPUNIT');

if (!!$envVar){
    return array(
        'phpunit' => array(
            'binary_path' => $envVar,
        ),
    );
} else {
    return array();
}

