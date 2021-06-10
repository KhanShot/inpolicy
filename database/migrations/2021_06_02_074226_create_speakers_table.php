<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpeakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email");
            $table->boolean("testing")->default(false);
            $table->string("surname")->nullable();
            $table->string("fathersname")->nullable();
            $table->string("position")->nullable();
            $table->string("organization")->nullable();
            $table->string("appeal")->nullable();
            $table->date("invitation_date")->nullable();
            $table->string("session_name")->nullable();
            $table->string("session_date")->nullable();
            $table->string("session_time_interval")->nullable();
            $table->timestamp("session_start_time")->nullable();
            $table->timestamp("session_end_time")->nullable();
            $table->string("language")->default("ru");
            $table->string("city")->nullable();
            $table->string("country")->nullable();
            $table->string("timezone")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('speakers');
    }
}
