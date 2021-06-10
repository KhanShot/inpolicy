<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email");
            $table->string("email_2")->nullable();
            $table->string("email_3")->nullable();
            $table->boolean("testing")->default(false);
            $table->string("surname")->nullable();
            $table->string("fathersname")->nullable();
            $table->string("position")->nullable();
            $table->string("organization")->nullable();
            $table->string("appeal")->nullable();
            $table->string("appeal_without_fathersname")->nullable();
            $table->date("invitation_date")->nullable();
            $table->string("language")->nullable()->default("ru");
            $table->string("city")->nullable();
            $table->string("certificate_url")->nullable();
            $table->string("country")->nullable();
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
        Schema::dropIfExists('partners');
    }
}
