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
        Schema::create('module_accesses', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('module_name');
            $table->string('access_right');
            $table->string('created_by');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');            
            $table->string('deleted_flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_accesses');
    }
};
