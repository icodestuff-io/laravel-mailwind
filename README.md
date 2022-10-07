<p align="center"><img src="https://github.com/icodestuff-io/laravel-mailwind/blob/main/mailwind-logo-transparent.png?raw=true" alt="Laravel Enum" width="250" style="margin-bottom: 20px"></p>
<p align="center">
<a href="https://packagist.org/packages/icodestuff/laravel-mailwind"><img src="https://img.shields.io/packagist/v/icodestuff/laravel-mailwind.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://packagist.org/packages/icodestuff/laravel-mailwind"><img src="https://img.shields.io/github/workflow/status/icodestuff-io/laravel-mailwind/run-tests?label=tests" alt="GitHub Tests Action Status"></a>
<a href="https://github.com/icodestuff-io/laravel-mailwind/actions?query=workflow%3A'Fix+PHP+code+style+issues'+branch%3Amain'"><img src="https://img.shields.io/github/workflow/status/icodestuff-io/laravel-mailwind/Fix%20PHP%20code%20style%20issues?label=code%20style" alt="GitHub Code Style Action Status"></a>
<a href="https://packagist.org/packages/icodestuff/laravel-mailwind"><img src="https://img.shields.io/packagist/dt/icodestuff/laravel-mailwind.svg?style=flat-square" alt="Total Downloads"></a>
</p>

![mailwind-example](./mailwind-screenshot.png)

## About Laravel MailWind
Use TailwindCSS to design your Laravel Mailables instead of relying on markdown or inline styles.

> It is required you have Node.js install on your machine as we use https://github.com/soheilpro/mailwind under the hood.


## Installation

You can install the package via composer:

```bash
composer require icodestuff/laravel-mailwind
```

You need to publish the views with:

```bash
php artisan vendor:publish --tag="mailwind-views"
```

## Usage
### 1. Create a template
By default, MailWind exports an example template called: `mailwind-example-template.blade.php`. 

If you want to create a new template, you can run: 

`php artisan mailwind:new MyTemplate`

which will generate the file `resources/views/vendor/mailwind/templates/my-template.blade.php`.

> In order to use MailWind, you **MUST** add new templates to the `resources/views/vendor/mailwind/templates`. Note, we don't currently support subdirectories within 
the `templates/` folder.

### 2. Compile your template
In order for your mailables to pickup on new template changes, you must use the MailWind compile command: 

`php artisan mailwind:compile`

which will generate compiled views within the `resources/views/vendor/mailwind/generated` directory. Note,
all generated files are ignored by git, so you will need to run the `php artisan mailwind:compile` in your deployment scripts similar to
`npm run prod`. 


### 3. Create a Mailable
Generate a Laravel mailable by running: 

`php artisan make:mail YourMailable`

### 4. Prepare your Mailable
To associate MailWind with a mailable, the mailable must implement the following trait:
~~~php 
namespace App\Mail;

use Icodestuff\MailWind\Traits\InteractsWithMailWind;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class YourMailable extends Mailable 
{

    use Queueable, SerializesModels, InteractsWithMailWind;
}
~~~

Then with the build method, use the template that we created within the `resources/views/vendor/mailwind/templates` directory
like so: 
~~~php 
/**
 * Build the message.
 *
 * @return $this
 */
public function build()
{
    return $this->view('mailwind::templates.mailwind-example-template')
        ->subject('MailWind Example Email');
}
~~~

### 5. Send the Mailable
Run `php artisan tinker` then paste

`Mail::to('test@example.com')->send(new App\Mail\YourMailable())`

to send out your email. If you are using Mailhog, you can visit http://localhost:8025/ to see the email: 
![Mailhog Screenshot](mailhog-screenshot.png)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Solomon Antoine](https://github.com/solomon04)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
