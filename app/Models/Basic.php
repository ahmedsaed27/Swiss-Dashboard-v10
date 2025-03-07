<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basic extends Model
{
    protected $table = 'basic_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'favicon',
        'logo',
        'website_title',
        'email_address',
        'contact_number',
        'address',
        'latitude',
        'longitude',
        'theme_version',
        'base_currency_symbol',
        'base_currency_symbol_position',
        'base_currency_text',
        'base_currency_text_position',
        'base_currency_rate',
        'primary_color',
        'secondary_color',
        'breadcrumb_overlay_color',
        'breadcrumb_overlay_opacity',
        'smtp_status',
        'smtp_host',
        'smtp_port',
        'encryption',
        'smtp_username',
        'smtp_password',
        'from_mail',
        'from_name',
        'to_mail',
        'breadcrumb',
        'disqus_status',
        'disqus_short_name',
        'google_recaptcha_status',
        'google_recaptcha_site_key',
        'google_recaptcha_secret_key',
        'whatsapp_status',
        'whatsapp_number',
        'whatsapp_header_title',
        'whatsapp_popup_status',
        'whatsapp_popup_message',
        'maintenance_img',
        'maintenance_status',
        'maintenance_msg',
        'bypass_token',
        'footer_logo',
        'admin_theme_version',
        'features_section_image',
        'testimonials_section_image',
        'course_categories_section_image',
        'notification_image',
        'google_adsense_publisher_id'
    ];

    public $timestamps = false;
}
