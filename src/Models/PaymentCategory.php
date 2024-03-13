<?php

namespace Narfu\Payments\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentCategory
 *
 * @property int|null $id
 * @property array|null $rules
 *
 * @package Narfu\Payments\Models
 */

class PaymentCategory extends Model
{
    protected $table = 'narfu_payment_categories';

    protected $casts = ["rules" => "array"];
}
