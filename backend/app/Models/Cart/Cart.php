<?php

namespace App\Models\Cart;

use App\Models\PaymentMethod\PaymentMethod;
use App\Modules\Cart\UseCase\Actions\CartUpdateActionUseCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $payment_method_id
 * @property int $total_items
 * @property int $installments
 * @property float $subtotal_amount
 * @property float $discount_amount
 * @property float $total_amount
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read PaymentMethod $payment_method
 * @property-read Collection<CartItem> $items
 *
 * @mixin Builder<Cart>
 */
class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'total_items',
        'installments',
        'subtotal_amount',
        'discount_amount',
        'total_amount',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::updated(function (Cart $item) {
            new CartUpdateActionUseCase()->execute($item);
        });
    }

    /**
     * @return HasOne<PaymentMethod, $this>
     */
    public function payment_method(): HasOne
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    /**
     * @return HasMany<CartItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }
}
