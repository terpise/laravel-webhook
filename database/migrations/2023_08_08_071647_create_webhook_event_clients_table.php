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
        Schema::create('webhook_event_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('webhook_event_id')->index();
            $table->unsignedBigInteger('webhook_client_id')->index();
            $table->tinyInteger('status')->default(0)
                ->comment('0 - init, 1 - read, 2 - done, 3 - fail');
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
        Schema::dropIfExists('webhook_event_clients');
    }
};
