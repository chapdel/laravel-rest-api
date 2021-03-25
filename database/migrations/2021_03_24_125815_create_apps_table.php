<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string("icon")->nullable();
            $table->string("package_name")->nullable();
            $table->boolean("iap")->default(false);
            $table->string("price")->nullable();
            $table->string("email")->nullable();
            $table->string("promo_video")->nullable();
            $table->string("privacy_policy")->nullable();
            $table->string("title");
            $table->enum("type", ['app', 'game'])->nullable()->default('app');
            $table->text("short_desc")->nullable();
            $table->text("website")->nullable();
            $table->text("address")->nullable();
            $table->text("available_in")->nullable();
            $table->text("not_available_in")->nullable();
            $table->longText("description")->nullable();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->foreignId('developper_id')->nullable()->constrained();
            $table->foreignId('language_id')->nullable()->constrained();
            $table->dateTime("banned_at")->nullable();
            $table->dateTime("released_at")->nullable();
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
        Schema::dropIfExists('apps');
    }
}
