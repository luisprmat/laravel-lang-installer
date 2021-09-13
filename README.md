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

### Add new language
After install a new laravel application with `Laravel >= 5.5` the package autodiscover system will register the new command `lang:add` and you can call with

```bash
php artisan lang:add <locale>
```
where `<locale>` refers to the short name of any of the [supported languages](README.md#supported-languages) 
> ### *Warnings*
> - **Add lang** action overwrites the language files so that you already had custom translations you could lose them.
> - When adding a language this package first consults the `composer.json` file to copy only the translations of the supported packages that are installed ([Laravel Breeze](https://laravel.com/docs/8.x/starter-kits#laravel-breeze), [Laravel Cashier](https://laravel.com/docs/8.x/billing), [Laravel Fortify](https://laravel.com/docs/8.x/fortify) and [Laravel Jetstream](https://jetstream.laravel.com/2.x/introduction.html) are supported) `resources/lang/<locale>.json`. So it is good that you first install the supported packages that you will use and then run the command `php artisan lang:add <locale>`
> - If this command does not receive arguments, the Spanish language [`es`] will be installed by default.

This command can take a unique argument (or none) that will be the short name of the language according to **ISO 15897**.

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

## Supported languages
`af`, `ar`, `az`, `be`, `bg`, `bn`, `bs`, `ca`, `cs`, `cy`, `da`, `de`, `de_CH`, `el`, `es`, `et`, `eu`, `fa`, `fi`, `fil`, `fr`, `gl`, `he`, `hi`, `hr`, `hu`, `hy`, `id`, `is`, `it`, `ja`, `ka`, `kk`, `km`, `kn`, `ko`, `lt`, `lv`, `mk`, `mn`, `mr`, `ms`, `nb`, `ne`, `nl`, `nn`, `oc`, `pl`, `ps`, `pt`, `pt_BR`, `ro`, `ru`, `sc`, `si`, `sk`, `sl`, `sq`, `sr_Cyrl`, `sr_Latn`, `sr_Latn_ME`, `sv`, `sw`, `tg`, `th`, `tk`, `tl`, `tr`, `ug`, `uk`, `ur`, `uz_Cyrl`, `uz_Latn`, `vi`, `zh_CN`, `zh_HK`, `zh_TW`

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

This package does not modify the translations, only copies them from [`laravel-lang/lang`](https://github.com/Laravel-Lang/lang/). So if you want to suggest changes in the translations you can make a PR to the [`laravel-lang/lang` package](https://github.com/Laravel-Lang/lang/blob/master/docs/contributing-to-dev.md)

## License
[MIT](LICENSE.md)

## Todo

- [ ] Allow merge translations instead of overwrite them.
- [ ] Add Command `lang:update` to update translations and detect new installed packages to update their translations.
