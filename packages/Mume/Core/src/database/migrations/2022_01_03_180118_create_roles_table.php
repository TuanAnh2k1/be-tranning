<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRolesTable
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 45);
                $table->boolean('is_active')->nullable();
                $table->string('description', 255)->nullable();

                $table->unsignedInteger('created_by_id');
                $table->timestamp('created_at')->useCurrent();

                $table->unsignedInteger('latest_update_by_id')->nullable();
                $table->timestamp('latest_update_at')->nullable();

                $table->unsignedInteger('deleted_by_id')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
