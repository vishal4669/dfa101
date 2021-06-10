<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('account')->nullable()->after('name');
            $table->string('telephone')->nullable()->after('account');
            $table->tinyInteger('status')->nullable()->after('telephone');

            $table->dropColumn(['remember_token', 'email_verified_at']);

        });

        Schema::rename('users', 'UsersDF_101');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('UsersDF_101', function (Blueprint $table) {
            $table->dropColumn('account');
            $table->dropColumn('telephone');
            $table->dropColumn('status');
        });
    }
}
