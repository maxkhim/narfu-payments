<?php

namespace Narfu\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes;

    protected $table = 'narfu_payment_categories';

    protected $casts = ["rules" => "array"];
}
