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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',40);
            $table->string('ap_paterno',40);
            $table->string('ap_materno',40);
            $table->string('telefono',10);
            $table->string('correo',60)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('codigoSMS',4)->nullable();
            $table->enum('mail_status',['0','1'])->default('0');
            $table->enum('active',['0','1'])->default('0');
            $table->unsignedBigInteger("role_id")->default('2');
            $table->unsignedBigInteger("casa_id")->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign("role_id")->references("id")->on("roles");
            $table->foreign("casa_id")->references("id")->on("casas");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
