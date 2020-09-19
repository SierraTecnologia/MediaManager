<?php


/*
|--------------------------------------------------------------------------
| Files Factory
|--------------------------------------------------------------------------
*/

$factory->define(\MediaManager\Models\File::class, function (Faker\Generator $faker) {
    return [
        'location' => 'files/dumb',
        'name' => 'dumbFile',
        'tags' => 'dumb, file',
        'mime' => 'txt',
        'size' => 24,
        'details' => 'dumb file',
        'user' => 1,
        'is_published' => 1,
        'order' => 1,
        'updated_at' => $faker->datetime(),
        'created_at' => $faker->datetime(),
    ];
});

/*
|--------------------------------------------------------------------------
| Images Factory
|--------------------------------------------------------------------------
*/

$factory->define(\MediaManager\Models\Image::class, function (Faker\Generator $faker) {
    return [
        'location' => 'files/dumb',
        'name' => 'dumb',
        'original_name' => 'dumb',
        'alt_tag' => 'dumb',
        'title_tag' => 'dumb',
        'is_published' => 1,
        'updated_at' => $faker->datetime(),
        'created_at' => $faker->datetime(),
    ];
});

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\MediaManager\Models\Photo::class, function (Faker\Generator $faker) {
    return [
        'created_by_user_id' => (new \App\Models\User)->newQuery()->inRandomOrder()->firstOrFail()->id,
        'path' => sprintf('/%s/%s.%s', str_random(12), str_random(5), str_random(3)),
        'avg_color' => $faker->hexColor,
        'metadata' => [],
    ];
});

$factory->define(\MediaManager\Models\Thumbnail::class, function (Faker\Generator $faker) {
    return [
        'path' => sprintf('/%s/%s.%s', str_random(12), str_random(5), str_random(3)),
        'width' => $faker->randomNumber(4),
        'height' => $faker->randomNumber(3),
    ];
});
