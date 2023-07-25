<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // build table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->set('role', ['viewer', 'admin'])->default('viewer');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // define initial user
        User::create([
            'name' => 'Saeed',
            'role' => 'admin',
            'email' => 'saeedx00014@gmail.com',
            'password' => bcrypt('saeedx00014@gmail.com'),
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
