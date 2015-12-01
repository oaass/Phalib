<?php

/**
 * @package Phalib
 * @subpackage Forms\Element
 */
namespace Phalib\Forms\Element;

/**
 * @uses Phalcon\Tag
 * @uses Phalcon\Forms\Element
 * @uses Phalcon\Forms\ElementInterface
 */
use Phalcon\Tag;
use Phalcon\Forms\Element;
use Phalcon\Forms\ElementInterface;

/**
 * Easily create form fields that's not default in Phalcon
 *
 * Usage:
 *  $el = new Phalib\Phalcon\Forms\Element\Custom('element');
 *  $el->setAttribute('type', 'dateTime');
 *
 * @package Phalib
 * @subpackage Forms\Element
 */
class Custom extends Element implements ElementInterface
{
    /**
     * Render element
     *
     * @param mixed $attributes
     *
     * @access public
     * @return string
     */
    public function render($attributes = null)
    {
        $attrs = $this->prepareAttributes($attributes);
        $method = sprintf("%sField", $attrs['type']);
        return Tag::{$method}($attrs);
    }
}