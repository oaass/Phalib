Phalib\Breadcrumbs - Breadcrumbs for PhalconPHP applications
===

This is a powerful and extremely easy to use breadcrumb add-on with optional multi-language support.

I recommend registering it with your application's services for even easier use

```
$di->set('crumbs', function() {
    return new \Phalib\Breadcrumbs\Breadcrumbs();
});
```

#How to use this add-on
###Adding a crumb with a link
```
$this->crumbs->add('home', '/', 'Home');
```

###Adding a crumb without a link (Normally the last one)
```
$this->crumbs->add('user', null, 'User', false);
```

###Render crumbs
```
$this->crumbs->render();
```

###Update an existing crumb
```
$params = ['label' => 'Account'];
$this->crumbs->update('user', $params);
```

###Delete a crumb
```
$this->crumbs->remove('user');
```

###Change crumb separator
```
$this->crumbs->setSeparator(' &raquo; ');
```

###Add multi-language support
```
$messages = [
    'crumb-home' => 'Home',
    'crumb-user' => 'User',
    'crumb-settings' => 'Settings',
    'crumb-profile' => 'Profile'
];
$translate = new \Phalcon\Translate\Adapter\NativeArray([
    'content'   => $messages
]);
$this->crumbs->useTranslation($translate);
$this->crumbs->setSeparator(' &raquo; ')
                ->add('home', '/', 'crumb-home')
                ->add('user', '/user', 'crumb-user')
                ->add('profile', '/user/profile', 'crumb-profile')
                ->add('settings', '', 'crumb-settings', false);
$this->crumbs->render();
```