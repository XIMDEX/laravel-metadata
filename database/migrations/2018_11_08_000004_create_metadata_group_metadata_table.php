<?php

use Metadata\Enums\Metadata;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetadataGroupMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_metadata_group', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('metadata_group_id');
            $table->unsignedInteger('metadata_id');
            $table->unsignedInteger('metadata_value_id')->nullable();
            $table->boolean('required')->default(false);
            $table->timestamps();

            $table->foreign('metadata_id')
                ->references('id')
                ->on('metadata')
                ->onDelete('cascade')
                ->nullable();

            $table->foreign('metadata_group_id')
                ->references('id')
                ->on('metadata_group')
                ->onDelete('cascade')
                ->nullable();

            $table->foreign('metadata_value_id')
                ->references('id')
                ->on('metadata_value')
                ->onDelete('set null')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata_group_metadata');
    }
}
