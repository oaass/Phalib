<?php

/**
 * Phalcon notification add on
 *
 * Provides a simple way of dealing with notifications
 */
namespace Phalib\Notifications;

/**
 * Phalcon notification add on
 *
 * Provides a simple way of dealing with notifications
 *
 * @package Phalib
 * @subpackage Notifications
 * @version 1.0
 * @author Ole Aass <ole@oleaass.com>
 */
class Notifications extends \Phalcon\Mvc\Model
{
    /**
     * Database ID
     * @var integer
     */
    public $id;

    /**
     * Main section
     * @var string
     */
    public $section;

    /**
     * Sub section
     * @var string
     */
    public $subsection;

    /**
     * Recipient ID
     * @var integer
     */
    public $recipient;

    /**
     * Notification message
     * @var string
     */
    public $message;

    /**
     * Redirect - Location the user will be redirected to when clicking the notification
     * @var string
     */
    public $redirect;

    /**
     * Seen - Marks if the user has seen the notificaiton or not
     * @var integer
     */
    public $seen = 0;

    /**
     * Created - Date and time when the notification was created
     * @var string
     */
    public $created;

    /**
     * Updated - Date and time when the notification was last updated
     * @var string
     */
    public $updated;

    /**
     * Actions performed before a new notification record is created
     *
     * @access public
     * @return void
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function beforeCreate()
    {
        $this->created = date('Y-m-d H:i:s');
    }

    /**
     * Actions performed before an existing notification record is updated
     *
     * @access public
     * @return void
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function beforeUpdate()
    {
        $this->updated = date('Y-m-d H:i:s');
    }

    /**
     * Add a new notification
     *
     * @param string $section
     * @param string $subsection
     * @param integer $recipient
     * @param string $message
     * @param string $redirect
     *
     * @access public
     * @return void
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function add($section, $subsection, $recipient, $message, $redirect)
    {
        $this->section = $section;
        $this->subsection = $subsection;
        $this->recipient = $recipient;
        $this->message = $message;
        $this->redirect = $redirect;
        self::save();
    }

    /**
     * Mark an existing notification record as seen
     *
     * @param integer $id Notification database ID
     *
     * @access public
     * @return void
     *
     * @since 1.0
     * @author Ole Aass <ole@oleaass.com>
     */
    public function mark($id)
    {
        $record = self::findFirst((int) $id);
        $record->seen = 1;
        return ($record->save());
    }

    public function findByRecipient($recipient, $seen = false)
    {
        return self::find([
            'conditions' => 'recipient = :recipient: AND seen = :seen:',
            'bind' => [
                'recipient' => $recipient,
                'seen' => (int) $seen
            ]
        ]);
    }
}