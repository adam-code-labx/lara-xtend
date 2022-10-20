<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('xtend_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('version');
            $table->json('data')->nullable();
            $table->string('namespace')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('xtend_packages');
    }
};
