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
class Widget extends Component implements WidgetInterface
{
    /**
     * @var string $viewPath
     */
    public $viewPath;

    /**
     * @var Phalcon\Mvc\View\Simple $view
     */
    public $view;

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

    public function initialize()
    {}
}