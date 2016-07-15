<?php
return array(
    'caching'           => false,
    'language'           => (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'French_France.1252':'fr_FR'), // Default language
    'language_fallback'  => (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'French_France.1252':'fr_FR'), // Fallback language when file isn't available for default language
    'locale'             => (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'French_France.1252':'fr_FR'), // PHP set_locale() setting, null to not set
    'locales'            => array(
        'en' => 'en_US',
        'fr' => (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'French_France.1252':'fr_FR'),
    ),
    'email_to_netmessage_only' => true
);
