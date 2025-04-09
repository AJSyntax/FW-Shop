<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('security_question_1_id')->nullable()->after('two_factor_confirmed_at');
            $table->string('security_answer_1')->nullable()->after('security_question_1_id');
            $table->foreignId('security_question_2_id')->nullable()->after('security_answer_1');
            $table->string('security_answer_2')->nullable()->after('security_question_2_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'security_question_1_id',
                'security_answer_1',
                'security_question_2_id',
                'security_answer_2'
            ]);
        });
    }
};
