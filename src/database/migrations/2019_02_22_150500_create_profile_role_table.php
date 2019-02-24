<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('security-starter.tables.associations.profile_roles'), function (Blueprint $table) {
            $table->integer('refProfile')->unsigned();
            $table->foreign('refProfile')->references('id')->on(config('security-starter.tables.profiles'));
            $table->integer('refRole')->unsigned();
            $table->foreign('refRole')->references('id')->on(config('security-starter.tables.roles'));
            $table->primary(['refProfile', 'refRole']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('security-starter.tables.associations.profile_roles'), function (Blueprint $table) {
            $table->dropForeign(['refProfile']);
            $table->dropForeign(['refRole']);
        });
        Schema::dropIfExists(config('security-starter.tables.associations.profile_roles'));
    }
}
