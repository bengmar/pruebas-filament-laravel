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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('brand_id')->constrained('brands');
            $table->string('title', 200);
            $table->string('subtitle', 200);
            $table->text('description');
            $table->integer('stock');
            $table->decimal('price', 10, 2);
            $table->integer('installments');
            $table->decimal('installment_price', 10, 2);
            $table->boolean('on_sale');
            $table->integer('views')->default(0); //para contar las vistas.
            $table->integer('discount')->default(0);
            $table->boolean('active')->default(true);
            $table->json('specs')->nullable();
            $table->string('image_1');        // obligatoria
            $table->string('image_2')->nullable(); // opcional
            $table->string('image_3')->nullable(); // opcional
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
