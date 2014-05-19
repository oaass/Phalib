<?php

/**
 * PhalconPHP breadcrumbs add-on
 *
 * Provides simple methods for adding breadcrumbs to your PhalconPHP powered applications
 */
namespace Phalib\Breadcrumbs;

/**
 * @uses InvalidArgumentException
 * @uses UnderflowException
 * @uses OutOfBoundsException
 */
use InvalidArgumentException,
    UnderflowException,
    OutOfBoundsException;
/**
 * PhalconPHP breadcrumbs add-on
 *
 * Provides simple methods for adding breadcrumbs to your PhalconPHP powered applications
 *
 * @package Phalib
 * @subpackage Breadcrumbs
 * @version 1.0
 * @author Ole Aass <ole@oleaass.com>
 */
class Breadcrumbs
{

    /**
     * Associative array holding breadcrumbs
     * @access public
     * @var array
     */
    public $crumbs = [];

    /**
     * Array holding output template
     * @access public
     * @var array
     */
    public $template = [
        'linked'        => '<a href="{{link}}">{{label}}</a>',
        'not-linked'    => '{{label}}'
    ];

    /**
     * Crumb separator
     * @access public
     * @var string
     */
    public $separator = ' / ';

    /**
     * Include multi-language support
     * @access public
     * @var null|\Phalcon\Translate\Adapter\NativeArray
     */
    public $translate = null;

    /**
     * Add a new breadcrumb
     *
     * @param string $id Internal identifier
     * @param string $link The link that will be used
     * @param string $label Text displayed in the breadcrumb trail
     * @param boolean $linked If false no link will be returned when rendering
     *
     * @throws InvalidArgumentException
     *
     * @access public
     * @return Phalib\Breadcrumbs\Breadcrumbs
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function add($id, $link, $label, $linked = true)
    {
        try {
            if (!is_string($id)) {
                $type = gettype($id);
                throw new InvalidArgumentException("Expected value of '\$id' to be string, {$type} given.");
            }

            if (!is_string($link) || !is_null($link)) {
                $type = gettype($type);
                throw new InvalidArgumentException("Expected value of '\$link' to be either string or null, {$type} given.");
            }

            if (!is_string($label)) {
                $type = gettype($label);
                throw new InvalidArgumentException("Expected value of '\$label' to be string, {$type} given.");
            }

            if (!is_bool($linked)) {
                $type = gettype($linked);
                throw new InvalidArgumentException("Expected value of '\$current' to be boolean, {$type} given.");
            }

            $this->crumbs[$id] = [
                'link' => $link,
                'label' => $label,
                'linked' => $linked
            ];

            return $this;

        } catch (InvalidArgumentException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        }
    }

    /**
     * Update an existing breadcrumb
     *
     * @param string $id Internal identifier
     * @param array $params Associative array holding the values that will be replaced
     *
     * @throws UnderflowException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     *
     * @access public
     * @return Phalib\Breadcrumbs\Breadcrumbs
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function update($id, $params)
    {
        try {
            if (empty($this->crumbs)) {
                throw new UnderflowException('Cannot update on an empty array');
            }

            if (!is_string($id)) {
                throw new InvalidArgumentException("Expected value of '\$id' to be string, {$type} given.");
            }

            if (!is_array($params)) {
                throw new InvalidArgumentException("Expected value of '\$params' to be an array, {$type} given.");
            }

            if (!array_key_exists($id, $this->crumbs)) {
                throw new OutOfBoundsException("No such id '{$id}' in array");
            }

            $this->crumbs[$id] = array_merge($this->crumbs[$id], $params);

            return $this;

        } catch (UnderflowException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        } catch (InvalidArgumentException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        } catch (OutOfBoundsException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        }
    }

    /**
     * Remove breadcrumb
     *
     * @param string $id Internal identifier
     *
     * @throws UnderflowException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     * 
     * @access public
     * @return Phalib\Breadcrumbs\Breadcrumbs
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function remove($id)
    {
        try {
            if (empty($this->crumbs)) {
                throw new UnderflowException('Cannot delete items from an empty array');
            }

            if (!is_string($id)) {
                throw new InvalidArgumentException("Expected value of '\$id' to be string, {$type} given.");
            }

            if (!array_key_exists($id, $this->crumbs)) {
                throw new OutOfBoundsException("No such id '{$id}' in array");
            }

            unset($this->crumbs[$id]);

            return $this;
        } catch (UnderflowException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        } catch (InvalidArgumentException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        } catch (OutOfBoundsException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        }
    }

    /**
     * Set crumb separator
     *
     * @param string $separator This will be placed between the crumbs
     * 
     * @throws Invalid argument exception
     *
     * @access public
     * @return Phalib\Breadcrumbs\Breadcrumbs
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function setSeparator($separator)
    {
        try {
            if (!is_string($separator)) {
                $type = gettype($separator);
                throw new InvalidArgumentException("Expected value of '\$separator' to be string, {$type} given.");
            }

            $this->separator = $separator;

            return $this;

        } catch (InvalidArgumentException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        }
    }

    /**
     * Set rendering template
     *
     * @param string $linked Markup used on crumbs with link
     * @param string $notLinked Markup used on crumbs without link
     *
     * @throws InvalidArgumentException
     *
     * @access public
     * @return Phalib\Breadcrumbs\Breadcrumbs
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function setTemplate($linked, $notLinked)
    {
        try {
            if (!is_string($notLinked)) {
                $type = gettype($notLinked);
                throw new InvalidArgumentException("Expected value of '\$notLinked' to be string, {$type} given.");
            }

            if (!is_string($linked)) {
                $type = gettype($linked);
                throw new InvalidArgumentException("Expected value of '\$linked' to be string, {$type} given.");
            }

            $this->template = [
                'linked'        => $linked,
                'not-linked'    => $notLinked
            ];

            return $this;

        } catch (InvalidArgumentException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        }
    }

    /**
     * Render breadcrumb output based on previously set template
     *
     * @throws UnderflowException
     *
     * @access public
     * @return void
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function render()
    {
        try {
            if (empty($this->crumbs)) {
                throw new UnderflowException('Cannot render an empty array');
            }

            $output = '';
            foreach ($this->crumbs as $key => $crumb) {
                if ($crumb['linked']) {
                    $output .= str_replace([
                        '{{link}}', '{{label}}'
                    ],[
                        $crumb['link'], 
                        (is_null($this->translate)) ? $crumb['label'] : $this->translate->_($crumb['label'])
                    ], $this->template['linked']);
                } else {
                    $output .= str_replace('{{label}}', 
                        (is_null($this->translate)) ? $crumb['label'] : $this->translate->_($crumb['label']),
                        $this->template['not-linked']);
                }
                $this->remove($key);
                $output .= (!empty($this->crumbs)) ? $this->separator : '';
            }

            echo $output;
        } catch (UnderflowException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        }
    }

    /**
     * Set multi-language support using \Phalcon\Translate\Adapter\NativeArray
     *
     * @throws InvalidArgumentException
     *
     * @param \Phalcon\Translate\Adapter\NativeArray $object Instance of Phalcon's Translate adapater
     *
     * @access public
     * @return void
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function useTranslation($translate)
    {
        try {
            if (!($translate instanceof \Phalcon\Translate\Adapter\NativeArray)) {
                throw new InvalidArgumentException("'\$translate' must be an instance of '\Phalcon\Translate\Adapter\NativeArray'");
            }

            $this->translate = $translate;

        } catch (InvalidArgumentException $e) {
            $message = '[' . __METHOD__ . '] ' . $e->getMessage();
            error_log($message);
        }
    }
}