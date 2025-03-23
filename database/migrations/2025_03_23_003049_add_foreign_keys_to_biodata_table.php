<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('biodata', function (Blueprint $table) {
            $table->foreign('created_by')->nullable()->references('id')->on('users')->cascadeOnDelete();
        });
    }
    
    public function down()
    {
        Schema::table('biodata', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
    }    
};
