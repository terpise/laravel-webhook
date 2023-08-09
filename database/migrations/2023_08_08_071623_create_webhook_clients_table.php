<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhook_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('secret', 100);
            $table->string('verify_token')->nullable();
            $table->string('callback_url')->nullable();
            $table->tinyInteger('subscribe')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhook_clients');
    }
};
