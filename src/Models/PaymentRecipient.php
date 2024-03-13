<?php

namespace Narfu\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class PaymentRecipient
 *
 * @property int|null $id
 * @property int|null $category_id
 * @property string|null $title
 * @property string|null $code
 * @property float|null $price
 * @property int|null $sort
 * @property array|null $rules
 *
 * @package Narfu\Payments\Models
 */

class PaymentRecipient extends Model
{
    protected $table = 'narfu_payment_recipients';

    protected $casts = [
        "price" => "double",
        "rules" => "array"
    ];

    public function category(): HasOne
    {
        return $this->hasOne(PaymentCategory::class, "id", "category_id");
    }
}
