<h3 align="center"><img src="https://user-images.githubusercontent.com/803299/42567830-6b6d3ad6-850b-11e8-9151-43021c92d8b7.png"></h3>

<h1>CKFinder 3 Package for Laravel 5</h1>

This repository contains the CKFinder 3 Package for Laravel 5.

## Installation

1. Add Composer dependency and install the bundle.

    ```bash
    composer require ckfinder/ckfinder-laravel-package
    ```

2. Enable the service provider.

    **If you're using Laravel 5.5 or higher you may skip this step.**

    ``` php
    // config/app.php

    'providers' => [
        // ...
        CKSource\CKFinderBridge\CKFinderServiceProvider::class,
    ],
    ```

3. Run the command to download the CKFinder code.

    After installing the Laravel package you need to download CKFinder code. It is not shipped
    with the package due to different license terms. To install it, run the following `artisan` command:

    ```bash
    php artisan ckfinder:download
    ```

    It will download the required code and place inside appropriate directory of the package (`vendor/ckfinder/ckfinder-laravel-package/`).

4. Publish CKFinder connector configuration and assets.

    ```bash
    php artisan vendor:publish --tag=ckfinder
    ```

    This will publish CKFinder assets to `public/js/ckfinder`, and CKFinder connector configuration to `config/ckfinder.php`.

5. Create a directory for CKFinder files and allow for write access to it. By default CKFinder expects it to be placed in `public/userfiles` (this can be altered in configuration).

    ```bash
    mkdir -m 777 public/userfiles
    ```

**NOTE:** Since usually setting permissions to 0777 is insecure, it is advisable to change the group ownership of the directory to the same user as Apache and add group write permissions instead. Please contact your system administrator in case of any doubts.

At this point you should see the connector JSON response after navigating to the `<APP BASE URL>/ckfinder/connector?command=Init` address.
Authentication for CKFinder is not configured yet, so you will see an error response saying that CKFinder is not enabled.

## Configuring Authentication

CKFinder connector authentication is managed by the `authentication` option in connector configuration file (`config/ckfinder.php`).
It expects a [PHP callable](http://php.net/manual/pl/language.types.callable.php) value, that after calling should return a Boolean value to decide if the user should have access to CKFinder.
As you can see the default service implementation is not complete and simply returns `false`.


A basic implementation that returns `true` from the `authenticate` callable (which is obviously **not secure**) can look like below:

```php
// config/ckfinder.php

$config['authentication'] = function () {
    return true;
};
```

Please have a look at [PHP Connector Documentation](https://docs.ckeditor.com/ckfinder/ckfinder3-php/configuration.html#configuration_options_authentication) to find out
more about this option.

## Configuration Options

The CKFinder connector configuration is taken from the `config/ckfinder.php` file.

To find out more about possible connector configuration options please refer to [CKFinder PHP Connector Documentation](http://docs.cksource.com/ckfinder3-php/configuration.html).

## Usage

The bundle code contains a couple of usage examples that you may find useful. To enable them uncomment the `ckfinder_examples`
route in `vendor/ckfinder/ckfinder-laravel-package/src/routes.php`:

```php
// vendor/ckfinder/ckfinder-laravel-package/src/routes.php

Route::any('/ckfinder/examples/{example?}', 'CKSource\CKFinderBridge\Controller\CKFinderController@examplesAction')
    ->name('ckfinder_examples');
```

After that you can navigate to the `<APP BASE URL>/ckfinder/examples` path and have a look at the list of available examples.
To find out about the code behind them, check the `views/samples` directory in the package (`vendor/ckfinder/ckfinder-laravel-package/views/samples/`).

### Including the Main CKFinder JavaScript File in Templates

To be able to use CKFinder on a web page you have to include the main CKFinder JavaScript file.
The preferred way to do that is including the CKFinder setup template, like presented below:

```blade
@include('ckfinder::setup')
```

The included template renders the required `script` tags and configures a valid connector path.

```html
<script type="text/javascript" src="/js/ckfinder/ckfinder.js"></script>
<script>CKFinder.config( { connectorPath: '/ckfinder/connector' } );</script>
```

---

## Useful links

 * [CKFinder 3 Usage Examples](https://docs.ckeditor.com/ckfinder/demo/ckfinder3/samples/widget.html)
 * [CKFinder 3 PHP Connector Documentation](https://docs.ckeditor.com/ckfinder/ckfinder3-php/)
 * [CKFinder 3 Developer Guide](https://docs.ckeditor.com/ckfinder/ckfinder3/)
 * [CKFinder 3 Issue Tracker](https://github.com/ckfinder/ckfinder)
