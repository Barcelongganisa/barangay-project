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
    Schema::table('service_requests', function (Blueprint $table) {
        $table->string('business_type')->nullable()->after('remarks');
        $table->integer('years_of_residency')->nullable()->after('business_type');
        $table->integer('family_size')->nullable()->after('years_of_residency');
        $table->decimal('monthly_income', 10, 2)->nullable()->after('family_size');
        $table->string('business_name')->nullable()->after('monthly_income');
        $table->text('business_address')->nullable()->after('business_name');
        $table->date('needed_by')->nullable()->after('business_address');
        $table->text('other_details')->nullable()->after('needed_by');
    });
}

public function down()
{
    Schema::table('service_requests', function (Blueprint $table) {
        $table->dropColumn([
            'business_type', 
            'years_of_residency', 
            'family_size', 
            'monthly_income', 
            'business_name', 
            'business_address', 
            'needed_by', 
            'other_details'
        ]);
    });
}
};
