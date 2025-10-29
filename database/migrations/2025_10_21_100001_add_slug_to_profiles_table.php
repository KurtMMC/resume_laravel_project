<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('email');
        });
        // Best-effort backfill: user-{user_id}
        try {
            $profiles = DB::table('profiles')->select('id', 'user_id', 'slug')->get();
            foreach ($profiles as $p) {
                if (!$p->slug) {
                    DB::table('profiles')->where('id', $p->id)->update([
                        'slug' => 'user-' . $p->user_id,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // ignore in case of permissions or during dry runs
        }
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
