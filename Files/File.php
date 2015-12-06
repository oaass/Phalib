<?php

/**
 * @package Phalib
 * @subpackage Files
 */
namespace Phalib\Files;

/**
 * @uses Phalcon\Http\Request\File
 * @uses Phalcon\Image\Adapter\GD
 */
use Phalcon\Http\Request\File as PhalconFile;
use Phalcon\Image\Adapter\GD as PhalconImageGD;
use Phalcon\Image\Adapter\Imagick as PhalconImageImagick;

/**
 * Wrapper class for Phalcon\Http\Request\File
 *
 * @author Ole Aass <ole@oleaass.com>
 * @package Phalib
 * @subpackage Files
 */
class File extends PhalconFile
{
    const ADAPTER_GD = 'gd';
    const ADAPTER_IMAGICK = 'imagick';

    /**
     * @var array $file Current file
     */
    public $file = [];

    /**
     * @var string $destination Upload path and filename
     */
    public $destination = '';

    /**
     * @var boolean $makeThumb If true, a thumbnail will be generated
     */
    public $makeThumb = false;

    /**
     * @var array $thumbSize Thumbnail size
     */
    public $thumbSize = [100,100];

    /**
     * @var boolean $thumbCrop If true the image will be cropped not scaled
     */
    public $thumbCrop = false;

    /**
     * @var null|string $thumbName Thumbnail path and filename
     */
    public $thumbName = null;

    /**
     * @var null|object $adapter Instance of eitehr Phalcon\Image\Adapter\GD or Phalcon\Image\Adapter\Imagick
     */
    public $adapter = null;

    /**
     * File class constructor
     *
     * @param array $file
     * @param mixed $key
     *
     * @access public
     * @return void
     */
    public function __construct(array $file, $key = NULL)
    {
        parent::__construct($file, $key);
        $this->file = $file;
        $this->setAdapter(self::ADAPTER_GD);
    }

    /**
     * Set adapter to use with image handling
     *
     * @param string $adapter
     *
     * @access public
     * @return void
     */
    public function setAdapter($adapter)
    {
        switch ($adapter) {
            case self::ADAPTER_GD:
                $this->adapter = 'Phalcon\Image\Adapter\GD';
                break;
            case self::ADAPTER_IMAGICK:
                $this->adapter = 'Phalcon\Image\Adapter\Imagick';
                break;
        }
    }

    /**
     * Check if file is an image
     *
     * @access public
     * @return boolean
     */
    public function isImage()
    {
        $type = $this->getType();
        return (in_array($type, [
            'image/png', 'image/gif', 'image/jpg', 'image/jpeg'
        ]));
    }

    /**
     * Create thumb after upload
     *
     * @param string $destination Path and filename of thumbnail
     * @param array $options
     *
     * @access public
     * @return void
     */
    public function createThumbAfterUpload($destination, array $options = array())
    {
        $this->makeThumb = true;
        $this->thumbName = $destination;

        if (isset($options['thumbSize']) && is_array($options['thumbSize'])) {
            $size = $options['thumbSize'];
            $crop = (isset($options['crop'])) ? $options['crop'] : $this->thumbCrop;
            $this->setThumbSize($size, $crop);
        }
    }

    /**
     * Set thumbnail size
     *
     * @param array $size
     * @param boolean $crop True if the thumbnail should be cropped instead of resized
     *
     * @access public
     * @return void
     */
    public function setThumbSize(array $size, $crop = false)
    {
        $this->thumbSize = $size;
        $this->thumbCrop = $crop;
    }

    /**
     * Upload file
     *
     * @param string $destination Path and filename to where the uploaded file will be stored
     *
     * @access public
     * @return mixed
     */
    public function upload($destination = null, $makeFolder = true)
    {
        if (empty($this->destination) && is_null($destination)) {
            throw new \RuntimeException('Missing upload destination');
        }

        $destination = (is_null($destination)) ? $this->destination : $destination;

        $dir = dirname($destination);
        if (!is_dir($dir) && $makeFolder) {
            mkdir($dir, 0777, true);
        }

        if (!$this->moveTo($destination)) {
            return false;
        }

        $this->_name = $destination;

        if ($this->isImage() && $this->makeThumb) {
            $this->makeThumbnailImage();
        }

        return true;
    }

    /**
     * Make thumbnail image
     *
     * @param string $destination Path and filename of thumbnail
     *
     * @access public
     * @return boolean
     */
    public function makeThumbnailImage($destination = null)
    {
        $this->thumbName = (is_null($destination)) ? $this->thumbName : $destination;

        $destination = str_replace([
                            '{filename}','{extension}'
                        ],[
                            basename($this->getName(), ".{$this->getExtension()}"),
                            $this->getExtension()
                        ], $this->thumbName);

        $image =  new $this->adapter($this->getName());

        $method = (!$this->thumbCrop) ? 'resize' : 'crop';
        call_user_func_array([$image, $method], $this->thumbSize);

        return ($image->save($destination));
    }

    /**
     * Rename file on the server
     *
     * @param string $new New path and filename
     *
     * @access public
     * @return void
     */
    public function rename($new)
    {
        if (!rename($this->getName(), $new)) {
            return false;
        }
        $this->_name = $new;
        return true;
    }

    /**
     * Delete file on server
     *
     * @access public
     * @return boolean
     */
    public function delete()
    {
        return unlink($this->getName());
    }

    /**
     * Copy file to another destination
     *
     * @param string $new New path and filename
     *
     * @access public
     * @return boolean
     */
    public function copy($new)
    {
        return copy($this->getName(), $new);
    }
}
