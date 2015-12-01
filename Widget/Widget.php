<?php

/**
 * @package Phalib
 * @subpackage Widget
 */
namespace Phalib\Widget;

/**
 * @uses Phalcon\Mvc\User\Component
 * @uses Phalcon\Mvc\View\Simple
 */
use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View\Simple as SimpleView;

/**
 * Widget base class
 *
 * @author Ole Aass <ole@oleaass.com>
 * @package Phalib
 * @subpackage Widget
 */
abstract class Widget extends Component implements WidgetInterface
{
    /**
     * @var string $viewPath
     */
    public $viewPath;

    /**
     * @var string $viewFile
     */
    public $viewFile;

    /**
     * @var Phalcon\Mvc\View\Simple $view
     */
    public $view;

    /**
     * @var array $attributes
     */
    public $attributes;

    /**
     * Widget base constructor
     *
     * @param array $attributes
     *
     * @access public
     * @return void
     */
    public function __construct(array $attributes = array())
    {
        $this->view = new SimpleView;
        $this->view->setViewsDir($this->viewPath);
        $this->initialize($attributes);
    }

    /**
     * Initialize widget
     *
     * @param array $attributes
     *
     * @access public
     * @return void
     */
    public function initialize(array $attributes = array())
    {}

    /**
     * Triggers when the object is called as a string
     *
     * @access public
     * @return string
     */
    public function __toString()
    {   
        if (!empty($this->viewPath) && !empty($this->viewFile)) {
            return $this->view->render($this->viewFile, $this->attributes);
        }

        return '';
    }
}