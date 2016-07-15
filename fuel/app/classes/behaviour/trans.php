<?php

namespace Behaviour;

use Fuel\Core\Lang;
use Fuel\Core\Config;
use Fuel\Core\Log;
use Exception;

class Trans
{
    public static function get($key, $params = array(), $default = null)
    {
        $key = trim($key);
        $translation = Lang::get($key, $params);
        
        if (empty($translation) && $default) {
            $translation = Lang::get($default, $params);
        }

        if (empty($translation)) {
            if (Config::get('translation_fatal_error')) {
                Log::error('No translation for key : ' . $key);
                throw new Exception('Extranet Exception : No translation for key : ' . $key);
            } else {
                $wrapper = Config::get('translation_wrapper');
                return $wrapper . $key . $wrapper;
            }
        } else {
            return $translation;
        }
    }
}
