<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Change request_date from date to datetime
            $table->datetime('request_date')->change();
        });
    }

    public function down()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Revert back to date if needed
            $table->date('request_date')->change();
        });
    }
};