<?php

use App\Models\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('file', 'attachable_id')) {
            Schema::table('file', function (Blueprint $table) {
                $table->integer('attachable_id')->nullable()->after('message_id');
            });
        }

        if (! Schema::hasColumn('file', 'attachable_type')) {
            Schema::table('file', function (Blueprint $table) {
                $table->string('attachable_type', 255)->nullable()->after('attachable_id');
            });
        }

        if (Schema::hasColumn('file', 'message_id')) {
            DB::table('file')
                ->whereNotNull('message_id')
                ->whereNull('attachable_id')
                ->update([
                    'attachable_id' => DB::raw('message_id'),
                    'attachable_type' => Message::class,
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('file', 'attachable_type')) {
            Schema::table('file', function (Blueprint $table) {
                $table->dropColumn('attachable_type');
            });
        }

        if (Schema::hasColumn('file', 'attachable_id')) {
            Schema::table('file', function (Blueprint $table) {
                $table->dropColumn('attachable_id');
            });
        }
    }
};