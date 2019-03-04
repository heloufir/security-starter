<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('security-starter.tables.associations.user_profiles'), function (Blueprint $table) {
            $table->integer('refUser')->unsigned();
            $table->foreign('refUser')->references('id')->on(app(config('auth.providers.users.model'))->getTable() ?: 'users');
            $table->integer('refProfile')->unsigned();
            $table->foreign('refProfile')->references('id')->on(config('security-starter.tables.profiles'));
            $table->primary(['refProfile', 'refUser']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('security-starter.tables.associations.user_profiles'), function (Blueprint $table) {
            $table->dropForeign(['refUser']);
            $table->dropForeign(['refProfile']);
        });
        Schema::dropIfExists(config('security-starter.tables.associations.user_profiles'));
    }
}
