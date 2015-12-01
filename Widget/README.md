Phalib\Widgets - Widget manager
===

With this package it's easy to create widgets for your application

#How to use this

###Create a basic widget
```php
namespace Your\Namespace\Widgets;

use Phalib\Widget\Widget as WidgetManager;
use Phalib\Widget\WidgetInterface;

class MyWidget extends WidgetManager implements WidgetInterface
{
    public function initialize()
    {
        echo 'This is a widget';
    }
}
```

###Create a widget with a view and attributes
```php
use Phalib\Widget\Widget as WidgetManager;
use Phalib\Widget\WidgetInterface;

class MyWidget extends WidgetManager implements WidgetInterface
{
    public $viewPath = __DIR__ . '/views/';
    public $attributes = [];
    
    public function initialize($attributes)
    {
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        return $this->view->render('index', $this->attributes);
    }
}
```

###Render widget without arguments
```php
echo new MyWidget;
```

###Render widget with argument
These arguments can be accessed in the view as any other views, like this: $foo, $bar
```php
echo new MyWidget([
    'foo' => 'bar',
    'bar' => 'baz'
]);
```