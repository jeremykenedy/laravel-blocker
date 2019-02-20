<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use jeremykenedy\LaravelBlocker\App\Models\Blocked;

class CreateLaravelBlockerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $blocked = new Blocked();
        $connection = $blocked->getConnectionName();
        $table = $blocked->getTableName();
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id');
                $table->string('typeId')->references('id')->on('laravel_blocker_types')->onDelete('cascade');
                $table->string('value');
                $table->longText('note')->nullable();
                $table->integer('userId')->references('id')->on('users')->onDelete('cascade')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
        $blocked = new Blocked();
        $connection = $blocked->getConnectionName();
        $table = $blocked->getTableName();

        Schema::connection($connection)->dropIfExists($table);
    }
}
