<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 13.06.19
 * Time: 6:56
 */

namespace App\Http\ViewHelpers;


class LinkMenu
{
    /** @var array */
    protected $_config;

    /** @var null|string  */
    protected $_activeLink = '';

    /** @var string  */
    protected $_view = 'components.links-block';

    /** @var array  */
    protected $_translations = [];

    /**
     * Static constructor
     *
     * @param $pathToConfig
     * @param null $activeLink
     * @param array $translations
     * @return LinkMenu
     */
    public static function make($pathToConfig, $activeLink = null, $translations = [])
    {
        return new self($pathToConfig, $activeLink, $translations);
    }

    public function setView($view) {
        $this->_view = $view;

        return $this;
    }

    protected function __construct($pathToConfig, $activeLink = null, $translations = [])
    {
        $this->_config = config($pathToConfig, [
            'links' => [],
        ]);
        $this->_activeLink = $activeLink;
        $this->_translations = $translations;
    }

    /**
     * Prepares data to render view
     *
     * @return array
     */
    protected function _prepareRenderData()
    {
        return [
            'links' => $this->_config['links'],
            'active' => $this->_activeLink,
            'trans' => $this->_translations,
        ];
    }

    /**
     * Render function
     *
     * @return string
     * @throws \Throwable
     */
    protected function _render()
    {
        return view($this->_view, $this->_prepareRenderData())->render();
    }

    /**
     * To String implementation to be able to pass Object into blade brackets
     *
     * @return string
     * @throws \Throwable
     */
    public function __toString()
    {
        return $this->_render();
    }
}