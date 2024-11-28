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
        Schema::create('lti_platforms', function (Blueprint $table) {
            $table->increments('platform_id');
            $table->string('name', 50);
            $table->string('issuer', 255)->nullable();
            $table->string('platform_client_id', 255)->nullable();
            $table->longText('private_key')->nullable();
            $table->longText('public_key')->nullable();
            $table->string('lti_version', 10)->nullable();
            $table->string('signature_method', 15)->nullable();
            $table->string('platform_name', 255)->nullable();
            $table->string('platform_version', 255)->nullable();
            $table->string('platform_guid', 1024)->nullable();
            $table->char('mgh_client_id', 36)->nullable();
            $table->text('profile')->nullable();
            $table->text('tool_proxy')->nullable();
            $table->text('jwkseturl')->nullable();
            $table->text('access_token')->nullable();
            $table->text('authorization_url')->nullable();
            // $table->string('logo', 255)->nullable();
            $table->tinyInteger('protected');
            $table->tinyInteger('enabled');
            $table->dateTime('enable_from')->nullable();
            $table->dateTime('enable_until')->nullable();
            $table->date('last_access')->nullable();
            $table->integer('created_by');
            $table->integer('modified_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();

            $table->foreign('mgh_client_id')->references('client_id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lti_platforms');
    }
};
