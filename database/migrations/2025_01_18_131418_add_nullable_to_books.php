<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->string('publisher')->nullable()->change();
            $table->date('published_date')->nullable()->change();
            $table->string('isbn')->nullable()->change();
            $table->string('cover_image')->nullable()->change();
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
            $table->string('publisher')->nullable(false)->change();
            $table->date('published_date')->nullable(false)->change();
            $table->string('isbn')->nullable(false)->change();
            $table->string('cover_image')->nullable(false)->change();
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
