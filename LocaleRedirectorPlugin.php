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
    public function getName()
    {
         return Craft::t('Locale Redirector');
    }

    public function getDescription()
    {
        return Craft::t('This plugin automatically redirects the visitor to their preferred locale');
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/pierrestoffe/localeredirector/blob/master/README.md';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/pierrestoffe/localeredirector/master/releases.json';
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Pierre Stoffe';
    }

    public function getDeveloperUrl()
    {
        return 'https://pierrestoffe.be';
    }

    public function hasCpSection()
    {
        return false;
    }
}