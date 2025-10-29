<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('profiles', 'profile_picture')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->string('profile_picture')->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('profiles', 'profile_picture')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->dropColumn('profile_picture');
            });
        }
    }
};
