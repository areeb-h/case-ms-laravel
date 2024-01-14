<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number');
            $table->string('case_title');
            $table->enum('case_type', [
                'HOMICIDE', 'THEFT', 'ASSAULT', 'DRUG_POSSESSION', 'DUI', 'FRAUD'
            ]);
            $table->string('case_role');  // Defendant or Appellant
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('lawyer_id');
            $table->string('status');  // For example: 'Open', 'Closed', etc.
            $table->text('description')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lawyer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases');
    }
}
