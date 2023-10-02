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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $product) {
                $product->increments('id');
                $product->string('name', 255);
                $product->string('sku', 45);
                $product->unsignedInteger('price');
                $product->json('images')->nullable();
                $product->integer('status')->nullable();
                $product->boolean('is_active')->nullable();
                $product->string('description', 255)->nullable();
                $product->json('category_ids')->nullable();

                $product->unsignedInteger('created_by_id');
                $product->timestamp('created_at')->useCurrent();

                $product->unsignedInteger('latest_update_by_id')->nullable();
                $product->timestamp('latest_update_at')->nullable();

                $product->unsignedInteger('deleted_by_id')->nullable();
                $product->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('products');
    }
};
