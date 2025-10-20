<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddStatusDatesToServiceRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->timestamp('submitted_date')->nullable();
            $table->timestamp('processing_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamp('declined_date')->nullable();
        });

        // Wait for the columns to be created, then update
        Schema::table('service_requests', function (Blueprint $table) {
            DB::table('service_requests')->update([
                'submitted_date' => DB::raw('request_date')
            ]);
        });
    }

    public function down()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['submitted_date', 'processing_date', 'completed_date', 'declined_date']);
        });
    }
}