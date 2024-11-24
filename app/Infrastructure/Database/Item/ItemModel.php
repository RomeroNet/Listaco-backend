<?php

namespace App\Infrastructure\Database\Item;

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
 * @method static Builder<static>|ItemModel newModelQuery()
 * @method static Builder<static>|ItemModel newQuery()
 * @method static Builder<static>|ItemModel query()
 * @method static Builder<static>|ItemModel whereCreatedAt($value)
 * @method static Builder<static>|ItemModel whereDone($value)
 * @method static Builder<static>|ItemModel whereId($value)
 * @method static Builder<static>|ItemModel whereListingId($value)
 * @method static Builder<static>|ItemModel whereName($value)
 * @method static Builder<static>|ItemModel whereUpdatedAt($value)
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
class ItemModel extends Model
{
    protected $table = 'items';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
