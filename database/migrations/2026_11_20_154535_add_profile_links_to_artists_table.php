<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the fields shown in the artist profile modal: a short role line and
 * optional social links. Links stay empty until real ones are added.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('headline', 120)->default('Professional artist')->after('name');
            $table->string('facebook_url')->nullable()->after('image_path');
            $table->string('github_url')->nullable()->after('facebook_url');
            $table->string('instagram_url')->nullable()->after('github_url');
            $table->string('email')->nullable()->after('instagram_url');
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn(['headline', 'facebook_url', 'github_url', 'instagram_url', 'email']);
        });
    }
};
