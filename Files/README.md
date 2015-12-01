Phalib\Files - File handling wrapper
===

This package is a wrapper to Phalcon's already existing File class


#How to use this add-on from a upload form
###Basic usage (Allow all files)
```php
if ($this->request->hasFiles()) {
    $files = new Phalib\Files\Files($_FILES);
    foreach ($files->getFiles() as $file) {
        $file->upload('/path/to/upload/' . md5(rand()) . '.'. $file->getExtension());
    }
}
```

###Custom validation rules (Only allows GIF's)
```php
if ($this->request->hasFiles()) {
    $files = new Phalib\Files\Files($_FILES, [
        'allowedTypes' => ['image/gif'],
        'messageType' => 'Invalid file type'
    ]);
    foreach ($files->getFiles() as $file) {
        $file->upload('/path/to/upload/' . md5(rand()) . '.'. $file->getExtension());
    }
}
```

###Use rule preset (Only images and not empty)
```php
if ($this->request->hasFiles()) {
    $files = new Phalib\Files\Files($_FILES, Phalib\Files\Files::RULE_IMAGE_ONLY_NOT_EMPTY);
    foreach ($files->getFiles() as $file) {
        $file->upload('/path/to/upload/' . md5(rand()) . '.'. $file->getExtension());
    }
}
```

###Create thumbnails
#### Resize image
```php
if ($this->request->hasFiles()) {
    $files = new Phalib\Files\Files($_FILES);
    foreach ($files->getFiles() as $file) {
        $file->createThumbAfterUpload('/path/to/thumbnail.' . $file->getExtension(), [
            'thumbSize' => [100,100]
        ]);
        $file->upload('/path/to/upload/' . md5(rand()) . '.'. $file->getExtension());
    }
}
```

####Crop image
```php
if ($this->request->hasFiles()) {
    $files = new Phalib\Files\Files($_FILES);
    foreach ($files->getFiles() as $file) {
        $file->createThumbAfterUpload('/path/to/thumbnail.' . $file->getExtension(), [
            'thumbSize' => [100,100],
            'crop' => true
        ]);
        $file->upload('/path/to/upload/' . md5(rand()) . '.'. $file->getExtension());
    }
}
```

####Modify original name
This example will add `_thumb` right after the filename
```php
if ($this->request->hasFiles()) {
    $files = new Phalib\Files\Files($_FILES);
    foreach ($files->getFiles() as $file) {
        $file->createThumbAfterUpload('/path/to/{filename}_thumb.{extension}', [
            'thumbSize' => [100,100],
            'crop' => true
        ]);
        $file->upload('/path/to/upload/' . md5(rand()) . '.'. $file->getExtension());
    }
}
```

###Upload fields with different names
Some times file upload fields has different names. We can use the name as reference when getting files

```php
if ($this->request->hasFiles()) {
    $files = new Phalib\Files\Files($_FILES);
    foreach ($files->getFiles('profile-image') as $file) {
        $file->upload('/path/to/upload/' . md5(rand()) . '.'. $file->getExtension());
    }
}
```

#Modifying files existing on the server

###Making an object of the file
```php
$filename = '/path/to/file.jpg';
$file = new Phalib\Files\File([
    'name' => $filename
]);
```

###Renaming the file
```php
$file->rename('/path/to/new/filename.jpg');
```

###Copy file
```php
$file->copy('/path/to/new/filename.jpg');
```

###Deleting file
```php
$file->delete();
```

###Make thumbnail of image
```php
$file->makeThumbnailImage('/path/to/thumbnail.jpg');