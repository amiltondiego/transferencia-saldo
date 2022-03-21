<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @property int   $payer_id
 * @property int   $payee_id
 * @property float $value
 * @property int   $type
 *
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Wallet extends Model
{
    use HasFactory;
}
