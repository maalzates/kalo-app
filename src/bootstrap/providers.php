<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\Auth\AuthServiceProvider::class,
    App\Modules\User\UserServiceProvider::class,
    App\Modules\Ingredient\IngredientServiceProvider::class,
    App\Modules\Recipe\RecipeServiceProvider::class,
    App\Modules\MealLog\MealLogServiceProvider::class,
    App\Modules\Biometric\BiometricServiceProvider::class,
    App\Modules\Role\RoleServiceProvider::class,
    App\Modules\Permission\PermissionServiceProvider::class,
    App\Modules\Macro\MacroServiceProvider::class,
    App\Modules\AI\AIServiceProvider::class,
];
