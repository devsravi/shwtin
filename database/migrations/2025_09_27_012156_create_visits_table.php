<?php

use App\Models\Url;
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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            // Relationships
            $table->foreignIdFor(User::class, 'user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Url::class, 'url_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            // Tracking info
            $table->string('ip_address')->nullable()->index();
            $table->text('user_agent')->nullable();

            $table->string('operating_system')->nullable();
            $table->string('operating_system_alias')->nullable();
            $table->string('operating_system_version')->nullable();

            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('engine')->nullable();

            $table->string('device_type')->nullable();
            $table->string('device_manufacturer')->nullable();
            $table->string('device_model')->nullable();

            // Location data
            $table->string('iso_code')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('timezone')->nullable();

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('long', 10, 7)->nullable();
            $table->string('continent')->nullable();
            $table->string('currency')->nullable();

            $table->string('is_default')->nullable();
            $table->string('referer_url')->nullable();

            $table->json('parser')->nullable();
            $table->json('geo_data')->nullable();
            $table->json('request_headers')->nullable();

            // Visit time
            $table->timestamp('visited_at')->useCurrent()->index();
            $table->softDeletes();
            $table->timestamps();

            // compound indexes for analytics

            // User activity over time
            $table->index(['user_id', 'visited_at'], 'idx_visits_user_time');

            // Page analytics over time
            $table->index(['url_id', 'visited_at'], 'idx_visits_url_time');

            // Geo + time (for regional analytics)
            $table->index(['iso_code', 'visited_at'], 'idx_visits_iso_time');
            $table->index(['country', 'visited_at'], 'idx_visits_country_time');
            $table->index(['city', 'state', 'visited_at'], 'idx_visits_city_state_time');

            // Browser/system analytics
            $table->index(['browser', 'visited_at'], 'idx_visits_browser_time');
            $table->index(['operating_system', 'visited_at'], 'idx_visits_os_time');

            // IP lookups (fraud prevention, rate-limiting)
            $table->index(['ip_address', 'visited_at'], 'idx_visits_ip_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
