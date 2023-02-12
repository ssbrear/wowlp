<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('realm');
            $table->string('realm_slug');
            $table->string('character_name_realm_name');
            $table->string('race');
            $table->string('class');
            $table->string('headshot');
            $table->string('guild')->nullable();
            $table->timestamps();

            $table->unique(['name', 'realm']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
    }
};
