<?php

namespace App\Models\PaymentMethod;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property float $fee_percent
 * @property float $discount_percent
 * @property int $max_discount_installments
 * @property int $max_installments
 * @property string $created_at
 * @property string $updated_at
 *
 * @mixin Builder<PaymentMethod>
 */
class PaymentMethod extends Model
{
    protected $table = 'payment_method';

    protected $fillable = [
        'name',
        'fee_percent',
        'discount_percent',
        'max_discount_installments',
        'max_installments',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'float',
    ];
}
