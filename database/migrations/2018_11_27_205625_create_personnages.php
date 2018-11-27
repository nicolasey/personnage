<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonnages extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        Schema::create("personnages", function (Blueprint $table) {
            $table->increments("id");
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedMediumInteger("owner");

            $table->boolean("active")->default(true);
            $table->boolean("isStaff")->default(false);
            $table->boolean("alive")->default(true);
            $table->string("name");
            $table->string("slug");
            $table->text("bio")->nullable();
            $table->string("title")->nullable();
        });
    }
}