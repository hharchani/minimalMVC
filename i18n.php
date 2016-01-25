<?php

class i18n {

    function __construct() {
        global $cfg;

        if ($cfg['i18n']['gettext']) {
            $domain = $cfg['i18n']['gettext']['domain'];
            $directory = $cfg['i18n']['gettext']['directory'];
            bindtextdomain($domain, $directory);
            textdomain($domain);
        }

        $client_locale = $this->get_client_locale();
        if ($client_locale) {
            $this->set_locale($client_locale);
        }
    }

    private function _set_locale($locale) {
        return
            putenv("LANG=" . $locale)
            && (setlocale(LC_ALL, $locale) !== false);
    }

    function set_locale($locale_string) {
        global $cfg;

        $locales = $cfg['i18n']['locales'];
        $languages = $cfg['i18n']['languages'];

        $locale = false;

        if (in_array($locale_string, $locales)) {
            $locale = $locale_string;
        } elseif (isset($languages[$locale_string])) {
            $locale = $languages[$locale_string];
        }

        if ($locale === false) {
            return false;
        }

        return $this->_set_locale($locale);
    }

    function get_locale_from_path($path) {
        $pathInfo = empty($path)? array() : explode('/', $path);

        if (!empty($pathInfo[1])) {
            if ($this->set_locale($pathInfo[1])) {
                array_splice($pathInfo, 1, 1);
            } else {
                header('Vary: Accept-Language');
            }
        }

        return implode('/', $pathInfo);
    }

    function get_client_locale() {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }
        else {
            return false;
        }
    }

}
