<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class CreateStudentsTable extends Migration
{

    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('city');
            $table->string('email')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        $faker = Faker::create();
        for ($i = 1; $i <= 300; $i++) {
            $cities = ['Ankara', 'İstanbul', 'İzmir'];
            $city = $cities[array_rand($cities)];
            DB::table('students')->insert([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'city' => $city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }


    public function down()
    {
        Schema::dropIfExists('students');
    }
}
