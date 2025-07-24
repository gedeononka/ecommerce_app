<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('address');
            $table->string('phone');
            $table->enum('payment_method', ['Paiement avant livraison', 'Paiement après livraison']);
            $table->enum('status', ['en attente', 'expédiée', 'livrée', 'annulée'])->default('en attente');
            $table->decimal('total_amount', 10, 2);
            $table->string(column: 'account_number')->nullable(); // 💡 Ajout du champ optionnel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
