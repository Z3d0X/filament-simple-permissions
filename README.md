# Simply define the permissions in the filament resource.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/z3d0x/filament-simple-permissions.svg?style=flat-square)](https://packagist.org/packages/z3d0x/filament-simple-permissions)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/z3d0x/filament-simple-permissions/run-tests?label=tests)](https://github.com/z3d0x/filament-simple-permissions/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/z3d0x/filament-simple-permissions/Check%20&%20fix%20styling?label=code%20style)](https://github.com/z3d0x/filament-simple-permissions/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/z3d0x/filament-simple-permissions.svg?style=flat-square)](https://packagist.org/packages/z3d0x/filament-simple-permissions)

Easily define permissions for [Filament](https://github.com/laravel-filament/filament) Resources & Relation Managers 

## Installation

You can install the package via composer:

```bash
composer require z3d0x/filament-simple-permissions
```
This package does not require any additional configuration after installation

## Usage

This package comes with two traits `HasResourcePermissions` and `HasRelationManagerPermissions`, which can be used in Filament's Resources and RelationManagers respectively.

To use simply use the trait in your Resource/RelationManger and define your permissions.

### Resource Example
```php
//UserResource.php
use Z3d0X\FilamentSimplePermissions\Concerns\HasResourcePermissions;

class UserResource extends Resource
{
    use HasResourcePermissions;

    protected static array $permissions = [
        'viewAny' => 'access-users',
        'view' => 'access-users',
        'create' => 'create-users',
        'update' => 'update-users',
        'delete' => ['update-users', 'delete-stuff'], //use an array if multiple permissions are needed
        'deleteAny' => false, //also supports boolean, to allow/disallow for all users
    ];
}
```

### RelationManager Example
```php
//PostsRelationManager.php
use Z3d0X\FilamentSimplePermissions\Concerns\HasRelationManagerPermissions;

class PostsRelationManager extends HasManyRelationManager
{
    use HasRelationManagerPermissions;

    protected static array $permissions = [
        'create' => 'create-posts',
        'update' => 'update-posts',
        'delete' => ['update-posts', 'delete-stuff'], //use an array if multiple permissions are needed
        'deleteAny' => false, //also supports boolean, to allow/disallow for all users

        //Supports relation manager specific actions.
        'viewForRecord' => 'access-posts',
        'associate' => 'update-posts',
        'dissociate' => 'update-posts',
        'dissociateAny' => false,
    ];
}
```

## Advanced Usage

For advanced usage, it is possible to define a static `getPermissions()` method, instead of `$permissions` property
```php
//PostsRelationManager.php
use Z3d0X\FilamentSimplePermissions\Concerns\HasRelationManagerPermissions;
use Illuminate\Database\Eloquent\Model;

class PostsRelationManager extends HasManyRelationManager
{
    use HasRelationManagerPermissions;

    protected static function getPermissions(): array
    {
        return [
            'viewForRecord' => fn (Model $ownerRecord) => $ownerRecord->user_id === auth()->id(),
            'update' => function (Model $record) {
                return $record->user_id === auth()->id();
            },
        ];
    }
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ziyaan Hassan](https://github.com/Z3d0X)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
