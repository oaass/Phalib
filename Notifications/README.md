Phalib\Notifications - Notifications for PhalconPHP applications
===

This is a simple little add on that provides some simple functions to implement a notification system in web applications.

Using this is really simple. I recommend registering it with the services for easier access

```
$di->set('notify', function() {
    $notify = new \Phalib\Notifications\Notifications();
    return $notify;
});
```

From this point on you can access it through ```$this->notify``` in all controllers, views, etc.

## Set up the database table

  
```
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subsection` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recipient` int(11) DEFAULT NULL,
  `message` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `redirect` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seen` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

## Usage

Create a new notification and store it in the database

```
$message = 'John Doe just left a comment on your gallery';
$this->notify->add('gallery', '56823', 1, $message, '/gallery/56823');
```

Get all unseen notifications
```
$notifications = $this->notify->findByRecipient(1);
foreach ($notifications as $notification) {
    // ...
}
```

Get all notifications including seen
```
$notifications = $this->notify->findByRecipient(1, true);
foreach ($notifications as $notification) {
    // ...
}
```

Mark notification as seen
```
$this->notify->mark(8);
```
This will mark notification with ID 8 as seen