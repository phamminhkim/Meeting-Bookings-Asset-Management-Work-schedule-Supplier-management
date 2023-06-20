<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\work\workflow\Wlworkflow;
use Faker\Generator as Faker;

$factory->define(Wlworkflow::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'workflow_type_id' => '1',
        'description' => $faker->text,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
