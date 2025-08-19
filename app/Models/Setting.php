<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];

    const KEY_PAYMENT_AMOUNT = 'payment_amount';
    const KEY_PAYMENT_RATE_PERCENT = 'payment_rate_percent';
    const KEY_REGISTRATION_STATUS = 'registration_status';
    const KEY_WEBSITE_LOGO_URL = 'website_logo';
    const KEY_EVENT_ABOUT = 'event_about';
    const KEY_EVENT_DESCRIPTION = 'event_description';
    const KEY_EVENT_NAME = 'event_name';
    const KEY_EVENT_SK = 'event_sk';
    const KEY_EVENT_BENEFITS_URL = 'benefits_url';
    const KEY_EVENT_MAPS_URL = 'maps_url';
    const KEY_EVENT_BANNER_URL = 'banner_url';

    const KEY_EVENT_FREE_MEMBER_LIMIT = 'free_member_limit';
    const KEY_EVENT_PAID_MEMBER_LIMIT = 'paid_member_limit';

    const KEY_EVENT_OCCASION_DATE = 'event_occasion_date';
    const KEY_EVENT_OCCASION_PLACE = 'event_occasion_place';

    const KEY_SUCCESS_EMAIL_NOTE = 'success_email_note';

    public static function get($key) {
        $setting = Setting::where('key', $key)->first();
        return $setting != null ? $setting->value : "";
    }

    public static function getUpdatedAt($key) {
        $setting = Setting::where('key', $key)->first();
        return $setting != null ? $setting->updated_at : "n.d.";
    }
}
