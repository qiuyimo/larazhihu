<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'title' => $faker->sentence,
        'content' => $faker->text,
    ];
});
