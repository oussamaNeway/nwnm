<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
    "redis" => array(
        "myconf" => array(
            "hostname" => "127.0.0.1",
            "port" => 6379,
            "password" => null,
            "timeout" => null,
            "database" => 0
        )
    )
);
