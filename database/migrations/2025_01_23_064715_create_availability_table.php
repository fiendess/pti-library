<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('availability', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('book_id'); 
            $table->unsignedBigInteger('location_id');
            $table->enum('status', ['available', 'borrowed', 'reserved'])->default('available'); // Status buku
            $table->integer('stock')->nullable(); 
            $table->decimal('price', 10, 2)->nullable(); 
            $table->timestamps(); 

            
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

           
            $table->index('book_id');
            $table->index('location_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability');
    }
};
