<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\User\UserServiceProvider::class,
    App\Modules\Ingredient\IngredientServiceProvider::class,
    App\Modules\Recipe\RecipeServiceProvider::class,
    App\Modules\MealLog\MealLogServiceProvider::class,
    App\Modules\Biometric\BiometricServiceProvider::class,
];
