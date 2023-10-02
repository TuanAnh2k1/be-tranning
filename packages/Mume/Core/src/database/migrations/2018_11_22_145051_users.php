<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class Users
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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 100)->unique();
                $table->string('email', 100)->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password', 255);
                $table->string('name', 100);
                $table->string('phone_number', 15)->nullable();
                $table->tinyInteger('gender')->nullable();
                $table->timestamp('birth_date')->nullable();
                $table->string('avatar', 255)->nullable();
                $table->string('description', 255)->nullable();
                $table->integer('role_id');
                $table->boolean('is_active')->nullable();
                $table->string('remember_token', 255)->nullable();

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
        Schema::dropIfExists('users');
    }
};
