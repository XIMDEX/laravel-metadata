<?php

use Metadata\Enums\Metadata;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetadataValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_values', function (Blueprint $table) {
            $table->increments('id');
            $table->text('value');
            $table->text('owner_id');
            $table->unsignedInteger('metadata_metadata_group_id');
            $table->timestamps();


            $table->foreign('metadata_metadata_group_id')
                ->references('id')
                ->on('metadata_metadata_group')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata');
    }
}
