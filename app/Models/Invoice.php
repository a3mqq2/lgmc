<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_number',
        'description',
        'licence_id',
        'pricing_id',
        'user_id',
        'amount',
        'status',
        'received_at',
        'relief_at',
        'received_by',
        'doctor_mail_id',
        'relief_by',
        'relief_reason',
        'branch_id',
        'doctor_id',
        'visitor_id',
        'category',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'received_at',
        'relief_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'status' => InvoiceStatus::class,
    ];

    /**
     * ✅ Relationship with Licence.
     *
     * @return BelongsTo
     */
    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }

    /**
     * ✅ Relationship with Pricing.
     *
     * @return BelongsTo
     */
    public function pricing(): BelongsTo
    {
        return $this->belongsTo(Pricing::class);
    }

   

    /**
     * ✅ Relationship with User (receiver).
     *
     * @return BelongsTo
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * ✅ Relationship with User (relief approver).
     *
     * @return BelongsTo
     */
    public function reliefBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'relief_by');
    }

    /**
     * ✅ Relationship with Branch.
     *
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * ✅ Check if the invoice is paid.
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->status->value === 'paid';
    }

    /**
     * ✅ Check if the invoice is in relief status.
     *
     * @return bool
     */
    public function isRelief(): bool
    {
        return $this->status->value === 'relief';
    }

    /**
     * ✅ Check if the invoice is unpaid.
     *
     * @return bool
     */
    public function isUnpaid(): bool
    {
        return $this->status->value === 'unpaid';
    }


    public function doctorMail()
    {
        return $this->belongsTo(DoctorMail::class);
    }
   

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }


    public function visitor()
    {
        return $this->belongsTo(Doctor::class,'visitor_id');
    }
}
