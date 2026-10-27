<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('subscription_id');

            $table->unsignedInteger('period_number');
            $table->date('period_start');
            $table->date('period_end');

            $table->decimal('allocated_amount', 12, 2);

            $table->timestamps();

            $table->foreign('payment_id')
                ->references('payment_id')
                ->on('payments')
                ->restrictOnDelete();

            $table->foreign('subscription_id')
                ->references('subscription_id')
                ->on('subscriptions')
                ->restrictOnDelete();

            $table->unique(
                [
                    'payment_id',
                    'subscription_id',
                    'period_start',
                    'period_end',
                ],
                'payment_allocations_payment_period_unique'
            );

            $table->index(
                ['subscription_id', 'period_start', 'period_end'],
                'payment_allocations_subscription_period_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};
