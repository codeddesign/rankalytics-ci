<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
#boovad-lang

// Originaly CodeIgniter i18n library by Jérôme Jaglale
// http://maestric.com/en/doc/php/codeigniter_i18n
// modification by Yeb Reitsma

/*
in case you use it with the HMVC modular extension
uncomment this and remove the other lines
load the MX_Loader class */

//require APPPATH."third_party/MX/Lang.php";

//class MY_Lang extends MX_Lang {

class MY_Lang extends CI_Lang
{


    /**************************************************
     * configuration
     ***************************************************/

    private $default = 'en';

    // languages
    private $languages = array(
        'en' => 'english',
        'de' => 'german',
    );

    // special URIs (not localized)
    private $special = array(//"admin",
    );

    // where to redirect if no language in URI
    private $uri;
    private $default_uri;
    private $lang_code;

    /**************************************************/


    function MY_Lang()
    {
        parent::__construct();

        global $CFG;
        global $URI;
        global $RTR;

        $this->uri = $URI->uri_string();
        $this->default_uri = $RTR->default_controller;

        $uri_segment = $this->get_uri_lang($this->uri);
        $this->lang_code = $uri_segment['lang'];

        $initialLang = null;
        if ($this->getLangCookie() !== NULL) {
            $initialLang = $this->getLangCookie();
        }

        // default:
        if ($this->lang_code == '') {
            $this->lang_code = $this->default;

            // check cookie:
            if ($this->getLangCookie() !== NULL) {
                $this->lang_code = $this->getLangCookie();
            }
        }

        // save cookie & set language for code-igniter:
        $this->setLangCookie($this->lang_code);
        $CFG->set_item('language', $this->languages[$this->lang_code]);

        // handle cache control issue. "clean-up" cache if language changed.
        $enableCacheControl = false;
        if ($initialLang != $this->lang_code) {
            $enableCacheControl = true;
        }

        // set default language if nothing is set in url and do nothing else:
        if ($uri_segment['lang'] == $this->default) {
            if($this->uri == $uri_segment['lang']) {
                $new_url = $CFG->config['base_url'];
            } else {
                $new_url = $CFG->config['base_url'] . str_replace('/en/', '', '/'.$this->uri);
            }

            if ($enableCacheControl) {
                header("Cache-Control: no-cache, must-revalidate");
            }

            header("Location:" . ($new_url), TRUE, 301);
        }

        if ($this->lang_code !== $this->default AND $uri_segment['lang'] !== $this->lang_code) {
            $new_url = $CFG->config['base_url'] . $this->lang_code . '/' . $this->uri;
            if ($enableCacheControl) {
                header("Cache-Control: no-cache, must-revalidate");
            }

            header("Location:" . ($new_url), TRUE, 302);

        }

        return true;
    }

    private function setLangCookie($abbr)
    {
        setcookie('u_lang', $abbr, (time() + 60 * 60 * 24 * 365), '/');
    }

    private function getLangCookie()
    {
        if (isset($_COOKIE['u_lang'])) {
            return $_COOKIE['u_lang'];
        } else {
            return null;
        }
    }

    // get current language
    // ex: return 'en' if language in CI config is 'english'
    function lang()
    {
        global $CFG;
        $language = $CFG->item('language');

        $lang = array_search($language, $this->languages);
        if ($lang) {
            return $lang;
        }

        return NULL;    // this should not happen
    }

    /**
     * @return mixed|null|string
     */
    function langLink() {
        $temp = $this->lang();
        return ($temp == $this->default) ? '' : '/'.$temp;
    }

    function is_special($lang_code)
    {
        if ((!empty($lang_code)) && (in_array($lang_code, $this->special)))
            return TRUE;
        else
            return FALSE;
    }


    function switch_uri($lang)
    {
        if ((!empty($this->uri)) && (array_key_exists($lang, $this->languages))) {

            if ($uri_segment = $this->get_uri_lang($this->uri)) {
                $uri_segment['parts'][0] = $lang;
                $uri = implode('/', $uri_segment['parts']);
            } else {
                $uri = $lang . '/' . $this->uri;
            }
        }

        return $uri;
    }

    //check if the language exists
    //when true returns an array with lang abbreviation + rest
    function get_uri_lang($uri = '')
    {
        if (!empty($uri)) {
            $uri = ($uri[0] == '/') ? substr($uri, 1) : $uri;

            $uri_expl = explode('/', $uri, 2);
            $uri_segment['lang'] = NULL;
            $uri_segment['parts'] = $uri_expl;

            if (array_key_exists($uri_expl[0], $this->languages)) {
                $uri_segment['lang'] = $uri_expl[0];
            }
            return $uri_segment;
        } else
            return FALSE;
    }


    // default language: first element of $this->languages
    function default_lang()
    {
        $browser_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ',') : '';
        $browser_lang = substr($browser_lang, 0, 2);
        if (array_key_exists($browser_lang, $this->languages))
            return $browser_lang;
        else {
            reset($this->languages);
            return key($this->languages);
        }
    }


    // add language segment to $uri (if appropriate)
    function localized($uri)
    {
        if (!empty($uri)) {
            $uri_segment = $this->get_uri_lang($uri);
            if (!$uri_segment['lang']) {

                if ((!$this->is_special($uri_segment['parts'][0])) && (!preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $uri))) {
                    $uri = $this->lang() . '/' . $uri;
                }
            }
        }
        return $uri;
    }


    /**
     * Same behavior as the parent method, but it can load the first defined
     * lang configuration to fill other languages gaps. This is very useful
     * because you don't have to update all your lang files during development
     * each time you update a text. If a constant is missing it will load
     * it in the first language configured in the array $languages. (OPB)
     *
     *
     * @param boolean $load_first_lang false to keep the old behavior. Please
     * modify the default value to true to use this feature without having to
     * modify your code
     */
    function load($langfile = '', $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $load_first_lang = false)
    {
        if ($load_first_lang) {
            reset($this->languages);
            $firstKey = key($this->languages);
            $firstValue = $this->languages[$firstKey];

            if ($this->lang_code != $firstKey) {
                $addedLang = parent::load($langfile, $firstValue, $return, $add_suffix, $alt_path);
                if ($addedLang) {
                    if ($add_suffix) {
                        $langfileToRemove = str_replace('.php', '', $langfile);
                        $langfileToRemove = str_replace('_lang.', '', $langfileToRemove) . '_lang';
                        $langfileToRemove .= '.php';
                    }
                    $this->is_loaded = array_diff($this->is_loaded, array($langfileToRemove));
                }
            }
        }
        return parent::load($langfile, $idiom, $return, $add_suffix, $alt_path);
    }

}

// END MY_Lang Class

/* End of file MY_Lang.php */
/* Location: ./application/core/MY_Lang.php */