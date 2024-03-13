<?php

namespace Narfu\Payments\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 *
 * @property int|null $id
 *
 * @package Narfu\Payments\Models
 */

class Payment extends Model
{
    protected $table = 'narfu_payments';

    protected $fillable = [
        "external_id",
        "amount",
        "metadata",
        "category_id",
        "recipient_id",
        "gate",
        "currency",
        "is_test",
    ];

    protected $casts = [
        "metadata" => "array"
    ];

}
