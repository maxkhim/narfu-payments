<?php

namespace Narfu\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaymentRecipient
 *
 * @property int|null $id
 * @property int|null $category_id
 * @property string|null $title
 * @property string|null $code
 * @property float|null $price
 * @property int|null $sort
 * @property double|null $tax
 * @property string|null $type
 * @property array|null $rules
 *
 * @package Narfu\Payments\Models
 */

class PaymentRecipient extends Model
{
    use SoftDeletes;

    protected $table = 'narfu_payment_recipients';

    protected $casts = [
        "price" => "double",
        "rules" => "array"
    ];

    public function category(): HasOne
    {
        return $this->hasOne(PaymentCategory::class, "id", "category_id");
    }

    public function getPaykeeperTaxTitle(): string
    {
        $result = "none";
        switch ($this->tax) {
            case 10:
                $result = "vat10";
                break;
            case 20:
                $result = "vat20";
                break;
            default:
                $result = "none";
                break;
        }

        return $result;
    }

    public function getPaykeeperTypeTitle(): string
    {
        $result = "service";
        switch ($this->type) {
            case "goods":
                $result = "goods";
                break;
            default:
                $result = "service";
                break;
        }

        return $result;
    }


}
