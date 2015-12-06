<?php

/**
 * @package Phalib
 * @subpackage Files
 */
namespace Phalib\Files;

/**
 * @uses Phalcon\Validation
 * @uses Phalcon\Validation\Validator\File
 */
use Phalcon\Validation;
use Phalcon\Validation\Validator\File as FileValidator;

/**
 * Factory class for handling multiple files
 *
 * @author Ole Aass <ole@oleaass.com>
 * @package Phalib
 * @subpackage Files
 */
class Files
{
    const RULE_IMAGE_ONLY_OR_EMPTY = 'imageOnlyOrEmpty';
    const RULE_IMAGE_ONLY_NOT_EMPTY = 'imageOnlyNotEmpty';

    /**
     * @var string $key $_FIELS index
     */
    public $key = '';

    /**
     * @var array $rawFiles Raw $_FILES array
     */
    public $rawFiles = [];

    /**
     * @var array $fixedFiles Modified of $rawFiles. Grouped by file not type
     */
    public $fixedFiles = [];

    /**
     * @var array $rules Set of rules which the file must obey
     */
    public $rules = [];

    /**
     * @var array $rulesPresets Set of predefined rules
     */
    public $rulesPresets = [
        'imageOnlyOrEmpty' => [
            'allowedTypes' => [
                'image/gif', 'image/png', 'image/jpeg', 'image/bmp'
            ],
            'messageType' => 'Invalid file type. Only images are allowed (gif, png, jpg, jpeg)'
        ],
        'imageOnlyNotEmpty' => [
            'allowedTypes' => [
                'image/gif', 'image/png', 'image/jpeg', 'image/bmp'
            ],
            'messageType' => 'Invalid file type. Only images are allowed (gif, png, jpg, jpeg)',
            'messageEmpty' => ':field cannot be empty'
        ]
    ];

    /**
     * @var array $errors Array of error messages
     */
    public $errors = [];

    public $adapter;

    /**
     * Class constructor
     *
     * @param array $files $_FILES
     * @param boolean $excludeEmpty
     *
     * @access public
     * @return void
     */
    public function __construct(array $files, $rules = null, $excludeEmpty = true)
    {
        $this->keys = array_keys($files);
        $this->rawFiles = $files;
        $this->buildFileArray($excludeEmpty);
        if (!is_null($rules)) {
            $this->setValidationRules($rules);
        }
    }

    /**
     * Convert from type based grouping to file based grouping
     *
     * @param boolean $excludeEmpty True will exclude empty array items
     *
     * @access public
     * @return void
     */
    public function buildFileArray($excludeEmpty = true)
    {

        foreach ($this->keys as $key) {
            $count = sizeof($this->rawFiles[$key]['name']);
            for ($i = 0; $i < $count; $i++) {
                if (!$excludeEmpty || ($excludeEmpty && !empty($this->rawFiles[$key]['name'][$i]))) {
                    $name = $this->rawFiles[$key]['name'];
                    $name = (is_array($name)) ? $name[$i] : $name;

                    $type = $this->rawFiles[$key]['type'];
                    $type = (is_array($type)) ? $type[$i]  : $type;

                    $tmpName = $this->rawFiles[$key]['tmp_name'];
                    $tmpName = (is_array($tmpName)) ? $tmpName[$i] : $tmpName;

                    $size = $this->rawFiles[$key]['size'];
                    $size = (is_array($size)) ? $size[$i] : $size;

                    $error = $this->rawFiles[$key]['error'];
                    $error = (is_array($error)) ? $error[$i] : $error;
                    $this->fixedFiles[$key][] = [
                        'name' => $name,
                        'type' => $type,
                        'tmp_name' => $tmpName,
                        'size' => $size,
                        'error' => $error
                    ];
                }
            }
        }
    }

    /**
     * Return array of file objects
     *
     * @access public
     * @return array
     */
    public function getFiles($key = null)
    {
        $list = (is_null($key)) ? $this->fixedFiles : $this->fixedFiles[$key];

        $files = [];
        foreach ($list as $item) {
            $file = new \Phalib\Files\File($item[0]);

            if (!is_null($this->adapter)) {
                $file->setAdapter($this->adapter);
            }

            $files[] = $file;
        }
        return $files;
    }

    /**
     * Set validation rules
     *
     * @param string|array $rules Set of validation rules, if string it must match a rules preset key
     * @param boolean $merge Merge in additional rules
     *
     * @access public
     * @return void
     */
    public function setValidationRules($rules, $merge = false)
    {
        if (!is_array($rules)) {
            $this->rules = $this->rulesPresets[$rules];
        } else {
            if ($merge) {
                $rules = array_merge($this->rules, $rules);
            }
            $this->rules = $rules;
        }
    }

    /**
     * Check if the files abides by the current rule set
     *
     * @access public
     * @return boolean
     */
    public function isValid()
    {
        $validation = new Validation;
        $file = new FileValidator($this->rules);

        $validation->bind($file, $this->rawFiles);
        $this->errors = $validation->validate();

        return (!(sizeof($this->errors) > 0));
    }
}
