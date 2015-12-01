<?php

/**
 * @package Phalib
 * @subpackage Widget
 */
namespace Phalib\Widget;

/**
 * Widget interface
 *
 * @author Ole Aass <ole@oleaass.com>
 * @package Phalib
 * @subpackage Widget
 */
interface WidgetInterface
{
    /**
     * Initialize widget
     *
     * @param array $attributes
     *
     * @access public
     * @return mixed
     */
    public function initialize(array $attributes = array());

    /**
     * Triggers when the object is called as a string
     *
     * @access public
     * @return string
     */
    public function __toString();
}