<?php

use App\Models\User;
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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->comment('User ID');
            $table->string('name')->nullable()->comment('Name of the policy (e.g., Privacy Policy, Terms of Service, Cookie Policy, etc.)');
            $table->string('version')->nullable()->default('1.0.0')->comment('Version of the policy');
            $table->text('description')->nullable()->comment('Description of the policy');
            $table->longText('content')->nullable()->comment('Content of the policy');
            $table->boolean('is_active')->index('idx_policies_is_active')->default(true)->comment('Is the policy active');
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'is_active'], 'idx_policies_user_is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
