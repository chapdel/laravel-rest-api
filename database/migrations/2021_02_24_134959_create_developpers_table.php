<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeveloppersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developpers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('poster')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('bio')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->dateTime("banned_at")->nullable();
            $table->foreignId('developper_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('developpers');
    }
}
