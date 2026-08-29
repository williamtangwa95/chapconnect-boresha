<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'user_package_id',
        'package_id',
        'package_name',
        'start_date',
        'end_date',
        'duration',
        'duration_unit',
        'amount',
        'amount_paid',
        'payment_status',
        'invoice_date',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_reference',
        'notes',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userPackage()
    {
        return $this->belongsTo(UserPackage::class, 'user_package_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate unique sequential invoice number
     */
    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $lastInvoice = self::where('invoice_number', 'like', "INV-{$year}-%")->orderBy('id', 'desc')->first();
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, 9));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        return 'INV-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
