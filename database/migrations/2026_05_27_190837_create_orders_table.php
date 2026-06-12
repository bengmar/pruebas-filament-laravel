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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Hacemos que el campo pueda ser null y le asignamos la regla
            $table->foreignId('user_id')
                ->nullable() //  Permite que la orden se quede sin usuario temporalmente
                ->constrained()
                ->nullOnDelete(); //  Si el usuario se borra definitivamente, el ID pasa a NULL, pero la orden sigue

            //datos de la venta
            $table->decimal('total', 10, 2);
            $table->string('payment_method');
            $table->string('status')->default('processing');

            // DATOS DE ENTREGA CONGELADOS (para el historial de compras)
            $table->string('customer_name');
            $table->string('customer_lastname');
            $table->string('customer_email');
            $table->string('delivery_street');
            $table->string('delivery_postal_code');

            // Relación con la ciudad por si necesitas métricas de envío por localidad (Todavia no estoy seguro si lo usaré)
            $table->foreignId('delivery_city_id')->constrained('cities');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
