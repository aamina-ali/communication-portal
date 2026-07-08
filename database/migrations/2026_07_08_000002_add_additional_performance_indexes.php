<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->index(['channel_id', 'status'], 'tasks_channel_status_idx');
        });

        Schema::table('message', function (Blueprint $table): void {
            $table->index(['channel_id', 'sent_at', 'message_id'], 'message_channel_sent_idx');
        });

        Schema::table('direct_message', function (Blueprint $table): void {
            $table->index(['conversation_id', 'sent_at', 'dm_message_id'], 'dm_conv_sent_idx');
        });

        Schema::table('pinned_message', function (Blueprint $table): void {
            $table->index(['pinnable_type', 'created_at'], 'pinned_message_type_created_idx');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->index('name', 'users_name_idx');
        });

        Schema::table('dm_read_state', function (Blueprint $table): void {
            $table->index(['user_id', 'conversation_id'], 'dm_read_state_user_conv_idx');
        });

        Schema::table('channel_read_state', function (Blueprint $table): void {
            $table->index(['user_id', 'channel_id'], 'channel_read_state_user_channel_idx');
        });
    }

    public function down(): void
    {
        Schema::table('channel_read_state', function (Blueprint $table): void {
            $table->dropIndex('channel_read_state_user_channel_idx');
        });

        Schema::table('dm_read_state', function (Blueprint $table): void {
            $table->dropIndex('dm_read_state_user_conv_idx');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('users_name_idx');
        });

        Schema::table('pinned_message', function (Blueprint $table): void {
            $table->dropIndex('pinned_message_type_created_idx');
        });

        Schema::table('direct_message', function (Blueprint $table): void {
            $table->dropIndex('dm_conv_sent_idx');
        });

        Schema::table('message', function (Blueprint $table): void {
            $table->dropIndex('message_channel_sent_idx');
        });

        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropIndex('tasks_channel_status_idx');
        });
    }
};
