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
        Schema::create('lti_resource_log', function (Blueprint $table) {
            $table->increments('resource_log_id');
            $table->string('resource_link_id', 50)->nullable();
            $table->string('resource_link_title', 255)->nullable();
            $table->string('message_type', 50)->nullable();
            $table->string('lti_resource_link', 255)->nullable();
            $table->string('resource_id', 50)->nullable();
            $table->text('roles', 50)->nullable();
            $table->string('launch_presentation', 50)->nullable();
            $table->string('tool_platform', 50)->nullable();
            $table->string('account_name', 50)->nullable();
            $table->string('context_title', 255)->nullable();
            $table->string('user_id', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('issuer', 50)->nullable();
            $table->string('client_id', 50)->nullable();
            $table->string('deployment_id', 100)->nullable();
            $table->string('nonce', 50)->nullable();
            $table->string('sub', 50)->nullable();
            $table->string('lti_version', 50)->nullable();
            $table->string('placement', 50)->nullable();
            $table->text('settings')->nullable();
            $table->string('exp', 50)->nullable();
            $table->text('headers', 50)->nullable();
            $table->text('payload', 50)->nullable();
            $table->string('referred', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lti_resource_log');
    }
};
