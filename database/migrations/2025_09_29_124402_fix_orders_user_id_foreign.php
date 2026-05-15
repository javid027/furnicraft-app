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
        Schema::table('orders', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['user_id']);
            
            // Add foreign key referencing customers.id
            $table->foreign('user_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop foreign key referencing customers
            $table->dropForeign(['user_id']);

            // Add back foreign key referencing users.id
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
