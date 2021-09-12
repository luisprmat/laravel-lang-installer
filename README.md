<h1 align="center">Laravel Lang Installer</h1>

<p align="center">
    <a href="https://packagist.org/packages/luisprmat/laravel-lang-installer">
        <img src="https://img.shields.io/packagist/dt/luisprmat/laravel-lang-installer" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/luisprmat/laravel-lang-installer">
        <img src="https://img.shields.io/packagist/v/luisprmat/laravel-lang-installer" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/luisprmat/laravel-lang-installer">
        <img src="https://img.shields.io/packagist/l/luisprmat/laravel-lang-installer" alt="License">
    </a>
</p>

This package helps us to quickly install the language files in a preferably fresh Laravel application.

## Installation

Use the [composer](https://getcomposer.org/) to install the package.

```bash
composer require luisprmat/laravel-lang-installer --dev
```

## Usage

After install a new laravel application with `Laravel >= 5.5` the package autodiscover system will register the new command `lang:add`.

This command can take a unique argument (or none) that will be the short name of the language according to **ISO 15897**.

If this command does not receive arguments, the Spanish language [`es`] will be installed by default.

This command also modifies the key `locale` in the `config/app.php` file to set the default language as passed through the parameter.

This command can also receive the following options:
- `-I` or `--inline` : Install `validation.php` with generic attributes, i. e no name for attribute (The placeholder `:attribute` is replaced by a generic name as _This field_, etc)
- `-D` or `--no-default` : This option prevents the `config/app.php` file from being modified. Therefore, the default language that appears in `config/app` will remain without changes.

### Examples

- Install Spanish as default language.
```bash
php artisan lang:add
```
or
```bash
php artisan lang:add es
```
- Install French as default language.

```bash
php artisan lang:add fr
```
- Install Brazilian Portuguese without changes in `config/app.php`.

```bash
php artisan lang:add pt_BR --no-default
```

- Install Aramaic language with `validation.php` without *attributes*.

```bash
php artisan lang:add ar --inline
```



## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

This package does not modify the translations, only copies them from [`laravel-lang/lang`](https://github.com/Laravel-Lang/lang/). So if you want to suggest changes in the translations you can make a PR to the [`laravel-lang/lang` package](https://github.com/Laravel-Lang/lang/blob/master/docs/contributing-to-dev.md)

## License
[MIT](LICENSE.md)
