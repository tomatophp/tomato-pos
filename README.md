![Screenshot](https://github.com/tomatophp/tomato-ecommerce/blob/master/art/screenshot.png)

# Tomato pos

Full POS system for tomato ordering and inventory

big thanks for [tailwind-pos](https://github.com/emsifa/tailwind-pos) for the tailwind POS Theme

## Installation

```bash
composer require tomatophp/tomato-pos
```

after install your package please run this command

```bash
php artisan tomato-pos:install
```

## Add Style

you need to add style.css to you app.css please on this file `resources/css/app.css` add this line after the imports 

```css
@import url('../../vendor/tomatophp/tomato-pos/resources/css/style.css');
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="tomato-pos-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="tomato-pos-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="tomato-pos-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="tomato-pos-migrations"
```

## Support

you can join our discord server to get support [TomatoPHP](https://discord.gg/VZc8nBJ3ZU)

## Docs

you can check docs of this package on [Docs](https://docs.tomatophp.com/plugins/tomato-pos)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
