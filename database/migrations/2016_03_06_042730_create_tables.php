<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjudicators', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name')->unique();
            $table->float('test_score')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name')->unique();
            $table->boolean('silent')->default(true);
            $table->timestamps();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type');
            $table->integer('round_id');
            $table->integer('evaluatee_id');
            $table->integer('evaluator_id');
            $table->double('score');
            $table->timestamps();

            $table->unique([
                'round_id', 'evaluatee_id', 'evaluator_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('adjudicators');
        Schema::drop('teams');
        Schema::drop('rounds');
        Schema::drop('feedbacks');
    }
}
