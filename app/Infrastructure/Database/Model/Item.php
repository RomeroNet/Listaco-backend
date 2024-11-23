<?php

namespace App\Infrastructure\Database\Model;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property bool $done
 * @property string $listing_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|Item newModelQuery()
 * @method static Builder<static>|Item newQuery()
 * @method static Builder<static>|Item query()
 * @method static Builder<static>|Item whereCreatedAt($value)
 * @method static Builder<static>|Item whereDone($value)
 * @method static Builder<static>|Item whereId($value)
 * @method static Builder<static>|Item whereListingId($value)
 * @method static Builder<static>|Item whereName($value)
 * @method static Builder<static>|Item whereUpdatedAt($value)
 *
 * @mixin Eloquent
 * @mixin Builder<self>
 *
 * @method array{
 *    id: string,
 *    name: string,
 *    done: bool,
 *    listing_id: string,
 * } toArray()
 */
class Item extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
