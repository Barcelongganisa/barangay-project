<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Rename id to payment_id
            $table->renameColumn('id', 'payment_id');
            
            // Add proper foreign key constraints
            $table->foreign('resident_id')
                  ->references('id')
                  ->on('barangay_residents')
                  ->onDelete('cascade');
                  
            $table->foreign('request_id')
                  ->references('id')
                  ->on('service_requests')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove foreign keys
            $table->dropForeign(['resident_id']);
            $table->dropForeign(['request_id']);
            
            // Rename back to id
            $table->renameColumn('payment_id', 'id');
        });
    }
};