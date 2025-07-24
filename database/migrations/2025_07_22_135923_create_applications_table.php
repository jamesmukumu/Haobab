<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("fullNames")->nullable(false);
            $table->string("emailAddress")->nullable(false);
            $table->string("phoneNumber")->nullable(false);
            $table->string("location")->nullable(false);
            $table->text("intro")->nullable(false);
            $table->text("workExperiences")->nullable(false);
            $table->text("resumePath")->nullable(false);
            $table->string("salaryExpectations")->nullable(false);
            $table->string("DOB")->nullable(false);
            $table->string("passportPhoto")->nullable(false);



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
