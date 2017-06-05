<?php
/**
 * Locale Redirector plugin for Craft CMS
 *
 * This plugin automatically redirects the visitor to their preferred locale
 *
 * @author    Pierre Stoffe
 * @copyright Copyright (c) 2017 Pierre Stoffe
 * @link      https://pierrestoffe.be
 * @package   LocaleRedirector
 * @since     1.0.0
 */

namespace Craft;

class LocaleRedirectorPlugin extends BasePlugin
{
    /**
     * Process if the current page might benefit from the locale redirect
     */
    public function init()
    {
        parent::init();
        
        if(!craft()->isConsole() && !craft()->request->isActionRequest() && !craft()->request->isLivePreview() && !craft()->request->isAjaxRequest() && craft()->request->isSiteRequest()) {
            craft()->localeRedirector->redirectCheck();
        }
    }
    /**
     * @return string
     */
    public function getName()
    {
         return Craft::t('Locale Redirector');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return Craft::t('This plugin automatically redirects the visitor to their preferred locale');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/pierrestoffe/localeredirector/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/pierrestoffe/localeredirector/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return 'Pierre Stoffe';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://pierrestoffe.be';
    }

    /**
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }
}