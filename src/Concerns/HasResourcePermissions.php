<?php

namespace Z3d0X\FilamentSimplePermissions\Concerns;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


trait HasResourcePermissions
{
    protected static function getPermissions(): array
    {
        return static::$permissions;
    }

    public static function can(string $action, ?Model $record = null): bool
    {
        $permissions = static::getPermissions();
        if (array_key_exists($action, $permissions)) {

            if (is_bool($permissions[$action])) {
                return $permissions[$action];
            }

            if ($permissions[$action] instanceof \Closure) {
                return $permissions[$action]($record);
            }

            $permissionsNeeded = Arr::wrap($permissions[$action]);
            foreach ($permissionsNeeded as $permission) {
                if (! Filament::auth()->user()->can($permission)) {
                    return false;
                }
            }

            return true;
        }
        //Fallback to filament's default can() method
        return parent::can($action, $record);
    }
}
