# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.0] - 2022-04-21
### Added
- Support for Laravel-Lang `^10.6` [[#8]](https://github.com/luisprmat/laravel-lang-installer/pull/8)

### Fixed
- Fix breaking changes with Laravel-lang/lang ^10.6 version, _split Laravel versions._

## [1.2.1] - 2022-02-09
### Added
- Support for Laravel `^9.0` and Laravel-lang `^10.4` [[#6]](https://github.com/luisprmat/laravel-lang-installer/pull/6)

## [1.1.2] - 2021-09-29
### Fixed
- Instalation message with `jetstream-ext` was included

## [1.1.1] - 2021-09-28
### Added
- Support for `jetstream-ext`
- Support for `laravel-lang/lang` `v10.1.7` (merge attributes)

## [1.1.0] - 2021-09-13
### Added
- Discover supported packages from `composer.json`.
- Optmize load of `<locale>.json` according to discovered packages [[#3]](https://github.com/luisprmat/laravel-lang-installer/pull/3).

## [1.0.2] - 2021-09-09
### Fixed
- *Validate argument*: Stop execution if language is not supported.

## [1.0.1] - 2021-09-08
### Fixed
- *Fix installation:* the `base_path` was wrong.

## [1.0.0] - 2021-09-08
Initial release
