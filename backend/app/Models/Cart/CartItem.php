<?php

namespace App\Models\Cart;

use App\Models\Product\Product;
use App\Modules\Cart\UseCase\Actions\CartUpdateActionUseCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read Cart $cart
 * @property-read Product $product
 *
 * @mixin Builder<CartItem>
 */
class CartItem extends Model
{
    protected $table = 'cart_item';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::created(function (CartItem $item) {
            new CartUpdateActionUseCase()->execute($item->cart);
        });
        static::deleted(function (CartItem $item) {
            new CartUpdateActionUseCase()->execute($item->cart);
        });
        static::updated(function (CartItem $item) {
            new CartUpdateActionUseCase()->execute($item->cart);
        });
    }

    /**
     * @return HasOne<Cart, $this>
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }

    /**
     * @return HasOne<Product, $this>
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
