<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentMethod extends Model
{
    protected $fillable = [
        'company',
        'account_name',
        'account_number',
        'logo_path',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to fetch active payment methods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get full URL or fallback icon for the payment method logo.
     */
    public function getLogoUrlAttribute()
    {
        if (!empty($this->logo_path)) {
            if (Str::startsWith($this->logo_path, ['http://', 'https://'])) {
                return $this->logo_path;
            }
            return asset($this->logo_path);
        }

        // Return standard provider logo fallback or default badge icon
        $comp = strtolower($this->company);
        if (str_contains($comp, 'crdb')) return asset('images/payment_methods/crdb.png');
        if (str_contains($comp, 'nmb')) return asset('images/payment_methods/nmb.png');
        if (str_contains($comp, 'tigo')) return asset('images/payment_methods/tigopesa.png');
        if (str_contains($comp, 'halo')) return asset('images/payment_methods/halopesa.png');
        if (str_contains($comp, 'mpesa') || str_contains($comp, 'vodacom')) return asset('images/payment_methods/mpesa.png');
        if (str_contains($comp, 'airtel')) return asset('images/payment_methods/airtel.png');

        return asset('images/default-avatar.png');
    }
}
