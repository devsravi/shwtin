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
        Schema::create('urls', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class, 'user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name')->nullable();
            $table->string('domain')->nullable();
            $table->text('destination_url');

            $urlKeyColumn = $table->string('url_key', 20)->unique();
            if (Schema::getConnection()->getConfig('driver') === 'mysql') {
                $urlKeyColumn->collation('utf8mb4_bin');
            }

            $table->string('default_short_url');
            $table->boolean('single_use')->default(false);
            $table->boolean('track_visits')->default(true);
            $table->integer('redirect_status_code')->default(302);

            $table->boolean('track_ip_address')->default(true);
            $table->boolean('track_operating_system')->default(true);
            $table->boolean('track_operating_system_version')->default(true);
            $table->boolean('track_browser')->default(true);
            $table->boolean('track_browser_version')->default(true);
            $table->boolean('track_referer_url')->default(true);
            $table->boolean('track_device_type')->default(true);

            $table->string('referer_url')->nullable();
            $table->boolean('forward_query_params')->default(false);
            $table->boolean('is_guest')->default(false);

            $table->timestamp('activated_at')->nullable()->useCurrent()->index();
            $table->timestamp('deactivated_at')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            // Compound indexes
            $table->index(['activated_at', 'deactivated_at'], 'idx_urls_active_dates');
            $table->index(['activated_at', 'deactivated_at', 'single_use'], 'idx_urls_active_window');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
