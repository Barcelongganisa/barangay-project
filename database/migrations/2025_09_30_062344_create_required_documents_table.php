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
    Schema::create('required_documents', function (Blueprint $table) {
        $table->increments('document_id'); // int(11) auto-increment
        $table->unsignedInteger('request_id'); // must match int(11)
        $table->string('document_type', 100);
        $table->string('file_path');
        $table->timestamps();

        // Correct foreign key reference
        $table->foreign('request_id')
              ->references('request_id')
              ->on('service_requests')
              ->onDelete('cascade');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('required_documents');
    }
};
