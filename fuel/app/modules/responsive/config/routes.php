<?php

return array(
    '_root_' => array('responsive/ResponsiveController/Index'),
    'responsive/index' => array('responsive/ResponsiveController/Index', 'name' => 'responsive_index'),
    'responsive/upload' => array('responsive/ResponsiveController/Upload', 'name' => 'responsive_upload'),
    'responsive/simplifie/:name' => array('responsive/SimpleController/Simplifie', 'name' => 'responsive_simplifie'),
);
