<?php
use App\Community;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Community::class, function (Faker $faker) {
    return [
        'id' => 1,
        'enable' => true,
        'user_id' => 1,
        'name' => $faker->country,
        'service_name' => $faker->company,
        'service_name_reading' => $faker->company,
        'url_path' => $faker->password,
        'hash_key' => $faker->password,
        'tumolink_enable' => false,
        'calendar_enable' => false,
        'calendar_public_iframe' => null,
        'calendar_secret_iframe' => null,
        'google_home_enable' => false,
        'admin_comment' => null,
        'created_at' => Carbon::now()->subDay(5),
        'updated_at' => Carbon::now()->subDay(3),
    ];
});
