<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class CreateStudentsTable extends Migration
{

    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('surname');
            $table->string('city');
            $table->string('email')->unique();
            //$table->foreign('lesson_id')->references('id')->on('lesson')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        $faker = Faker::create();
        $dataNumber = 300;
        for ($i = 1; $i <= $dataNumber; $i++) {
            $cities = ['Ankara', 'İstanbul', 'İzmir'];
            $city = $cities[array_rand($cities)];
            DB::table('students')->insert([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'city' => $city,
                'lesson_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $studentIds = DB::table('students')->pluck('id')->toArray(); // Öğrencilerin ID'lerini alıyoruz

        $students = \App\Models\Student::all(); // Öğrencileri alıyoruz

        foreach ($students as $student) {

            $parentMenuId = (rand(0, 1) == 1) ? Arr::random($studentIds) : null;

            $student->parent_id = $parentMenuId;
            $student->save();
        }

    }


    public function down()
    {
        Schema::dropIfExists('students');
    }
}
