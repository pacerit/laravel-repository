<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\PacerIT\LaravelRepository\Tests\Resources\Entities\Test::class, function (Faker $faker) {
    return [
        'name'       => \Illuminate\Support\Str::random(),
        'deleted_at' => null,
    ];
});
