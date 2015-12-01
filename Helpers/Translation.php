<?php

/**
 * @package Phalib
 * @subpackage Helpers
 */
namespace Phalib\Helpers;

/**
 * @uses Phalcon\Translate\Adapter\NativeArray
 * @uses Phalcon\Http\Request
 */
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Http\Request;

/**
 * Translation helper class
 *
 * @author Ole Aass <ole@oleaass.com>
 * @package Phalib
 * @subpackage Helpers
 */
class Translation extends Request
{
    /**
     * @var string $path Path to where the language files are located
     */
    public $path;

    /**
     * @var Phalcon\Http\Request $request Instance of Phalcon's request object
     */
    public $request;

    /**
     * @var string $defaultLanguage Falls back to this language if best language doesn't exist
     */
    public $defaultLanguage;

    /**
     * Translation class constructor
     *
     * @param Phalcon\Http\Request $request Instance of Phalcon's request object
     * @param string $path Path to where the language files are located
     * @param string $defaultLanguage Falls back to this language if best language doesn't exist
     *
     * @access public
     * @return void
     */
    public function __construct(Request $request, $path, $defaultLanguage = 'en')
    {
        // Make sure it's pointing to a valid directory
        if (!is_dir($path)) {
            throw new \InvalidArgumentException('Language path does not exist');
        }

        $this->request = $request;
        $this->path = rtrim($path, '/');
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * Get translation
     *
     * @access public
     * @return void
     */
    public function getTranslation()
    {
        $language = $this->request->getBestLanguage();
        $fullpath = "{$this->path}/{$language}.php";

        if (false !== ($realpath = realpath($fullpath))) {
            require $realpath;
        } else {
            require "{$this->path}/{$this->defaultLanguage}.php";
        }

        return new NativeArray([
            "content" => $messages
        ]);
    }
}