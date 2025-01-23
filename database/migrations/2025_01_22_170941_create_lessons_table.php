<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CreateLessonsTable extends Migration
{
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('akts');
            $table->string('code')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        $faker = Faker::create();
        for ($i = 1; $i <= 100; $i++) {
            $akts = [4, 8, 12, 16, 24];
            $akts_value = $akts[array_rand($akts)];

            DB::table('lessons')->insert([
                'name' => $faker->word,
                'akts' => $akts_value,
                'code' => $faker->unique()->bothify('###-???'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
