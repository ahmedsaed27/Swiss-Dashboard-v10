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
        Schema::create('basic_settings', function (Blueprint $table) {
            $table->id();
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->string('website_title')->nullable();
            $table->string('email_address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude' , 8 , 5)->nullable();
            $table->decimal('longitude' , 8 , 5)->nullable();
            $table->tinyInteger('theme_version')->default(0);
            $table->string('base_currency_symbol')->nullable();
            $table->string('base_currency_symbol_position')->nullable();
            $table->string('base_currency_text')->nullable();
            $table->string('base_currency_text_position')->nullable();
            $table->decimal('base_currency_rate' , 8 , 2)->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('breadcrumb_overlay_color')->nullable();
            $table->decimal('breadcrumb_overlay_opacity' , 4 , 2)->nullable();
            $table->tinyInteger('smtp_status')->nullable();
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('encryption')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('from_mail')->nullable();
            $table->string('from_name')->nullable();
            $table->string('to_mail')->nullable();
            $table->string('breadcrumb')->nullable();
            $table->tinyInteger('disqus_status')->nullable();
            $table->string('disqus_short_name')->nullable();
            $table->tinyInteger('google_recaptcha_status')->nullable();
            $table->string('google_recaptcha_site_key')->nullable();
            $table->string('google_recaptcha_secret_key')->nullable();
            $table->tinyInteger('whatsapp_status')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('whatsapp_header_title')->nullable();
            $table->tinyInteger('whatsapp_popup_status')->nullable();
            $table->longText('whatsapp_popup_message')->nullable();
            $table->string('maintenance_img')->nullable();
            $table->tinyInteger('maintenance_status')->nullable();
            $table->text('maintenance_msg')->nullable();
            $table->string('bypass_token')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('admin_theme_version');
            $table->string('features_section_image')->nullable();
            $table->string('testimonials_section_image')->nullable();
            $table->string('course_categories_section_image')->nullable();
            $table->string('notification_image')->nullable();
            $table->string('google_adsense_publisher_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basics');
    }
};
