<?php
/**
 * Locale Redirector plugin for Craft CMS
 *
 * Locale Redirector Service
 *
 * @author    Pierre Stoffe
 * @copyright Copyright (c) 2017 Pierre Stoffe
 * @link      https://pierrestoffe.be
 * @package   LocaleRedirector
 * @since     1.0.0
 */

namespace Craft;

require_once __DIR__ . '/../vendor/autoload.php';

use Jaybizzle\CrawlerDetect\CrawlerDetect;

class LocaleRedirectorService extends BaseApplicationComponent
{
    protected $base_url;
    protected $query_string;
    public $cookie_name;
    public $cookie_expire;

    public function __construct()
    {
        $this->base_url = craft()->request->getPath();
        parse_str(html_entity_decode(craft()->request->getQueryStringWithoutPath()), $this->query_string);
        $this->cookie_name = 'locale';
        $this->cookie_expire = time() + 60 * 60 * 24 * 30;
    }

    /**
     * Process all parameters and act accordingly
     */
    public function redirectCheck()
    {
        $guessed_locale = (isset($this->query_string['locale'])) ? $this->query_string['locale'] : null;
        $new_localized_url = null;
        $CrawlerDetect = new CrawlerDetect;

        // If a crawler is detected, stop here
        if($CrawlerDetect->isCrawler()) {
            return false;
        }

        // If the locale is forced by the query string
        if(!empty(craft()->request->getQuery('locale'))) {
            $this->setCookieLocale($guessed_locale);
        } else {
            $guessed_locale = $this->getGuessedLocale();

            // If we could not detect the locale, use the default locale
            if (empty($guessed_locale)) {
                $guessed_locale = craft()->config->get('defaultLocale', 'localeredirector');
            }

            $new_localized_url = $this->getNewLocalizedUrl($guessed_locale);
            $this->setCookieLocale($guessed_locale);
        }

        // Redirect (302) if redirecting to another locale is necessary
        if(!empty($new_localized_url)) {
            craft()->request->redirect($new_localized_url, true, 302);
        }
    }

    /**
     * Check if a match can be made between the browser's locales and Craft's locales
     * @return string
     */
    protected function getLocaleMatch()
    {
        $locale_match = null;
        $browser_locales = craft()->request->getBrowserLanguages();
        $site_locales = craft()->i18n->getSiteLocaleIds();

        // Loop through the list of available locale and find a perfect match
        if(!empty($browser_locales)) {
            foreach($browser_locales as $locale) {
                if(in_array($locale, $site_locales)) {
                    $locale_match = $locale;
                    break;
                }
            }
        }

        // Otherwise, let's try again, removing country codes in browser locales
        if(empty($locale_match) && !empty($browser_locales)) {
            foreach($browser_locales as $browser_locale) {
                $browser_locale_short = substr($browser_locale, 0, 2);

                foreach($site_locales as $site_locale) {
                    if($site_locale == $browser_locale_short) {
                        $locale_match = $site_locale;
                        break;
                    }
                }
            }
        }

        // Otherwise, let's try again, removing country codes in browser locales
        // and in site locales
        if(empty($locale_match) && !empty($browser_locales)) {
            foreach($browser_locales as $browser_locale) {
                $browser_locale_short = substr($browser_locale, 0, 2);

                foreach($site_locales as $site_locale) {
                    $site_locale_short = substr($site_locale, 0, 2);

                    if($site_locale_short == $browser_locale_short) {
                        $locale_match = $site_locale;
                        break;
                    }
                }
            }
        }

        return $locale_match;
    }

    /**
     * Check if a locale can be guessed
     * @return string
     */
    protected function getGuessedLocale()
    {
        $guessed_locale = null;
        $cookie_locale = $this->getCookieLocale();

        if(!empty($cookie_locale)) {
            return $cookie_locale;
        }

        $guessed_locale = $this->getLocaleMatch();

        if(!empty($guessed_locale)) {
            return $guessed_locale;
        }

        return null;
    }

    /**
     * Get the url in a new locale
     * @param string $locale
     * @return string
     */
    protected function getNewLocalizedUrl($locale)
    {
        $current_locale = CraftVariable::locale();

        if($current_locale == $locale) {
            return null;
        }

        $element = craft()->urlManager->getMatchedElement();

        if(!$element) {
            return null;
        }

        // If the element is a section/category and doesn't exist in required locale
        if($element && in_array($element->getElementType(), array(ElementType::Entry, ElementType::Category))) {
            $element_has_locale = in_array($locale, array_keys($element->locales));

            if(!$element_has_locale) {
                return null;
            }
        }

        $criteria = craft()->elements->getCriteria($element->getElementType());
        $criteria->id = $element->getAttribute('id');
        $criteria->locale = $locale;
        $entry = $criteria->first();

        if(!$entry) {
            return null;
        }

        $this->query_string['locale'] = $locale;
        $new_localized_url = UrlHelper::getSiteUrl($entry->uri, $this->query_string, null, $locale);

        // By default, the homepage comes with a '__home__' string into it. Let's remove it.
        $new_localized_url = str_replace('__home__', '', $new_localized_url);

        return $new_localized_url;
    }

    /**
     * Set the locale cookie
     * @param string $locale
     */
    protected function setCookieLocale($locale)
    {
        $cookie_locale = $this->getCookieLocale();

        if($cookie_locale != $locale) {
            setcookie($this->cookie_name, $locale, $this->cookie_expire, '/');
            $_COOKIE[$this->cookie_name] = $locale;
        }
    }

    /**
     * Get the value of the local cookie
     * @return string
     */
    protected function getCookieLocale()
    {
        $cookie_locale = (isset($_COOKIE['locale'])) ? $_COOKIE['locale'] : null;

        return $cookie_locale;
    }

}
