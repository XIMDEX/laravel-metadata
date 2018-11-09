<?php

use Metadata\Enums\Metadata;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetadataGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('metadata_section_id')->nullable();
            $table->string('name', 255);
            $table->timestamps();

            $table->foreign('metadata_section_id')
                ->references('id')
                ->on('metadata_sections')
                ->onDelete('cascade')
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
        Schema::dropIfExists('metadata_group');
    }
}
