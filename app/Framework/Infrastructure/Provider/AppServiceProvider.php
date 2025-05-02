<?php

namespace App\Framework\Infrastructure\Provider;

use App\Common\Uuid\Domain\UuidFactoryInterface;
use App\Common\Uuid\Infrastructure\Factory\RamseyUuidFactory;
use App\Item\Domain\ItemRepositoryInterface;
use App\Item\Infrastructure\Database\Model\ItemModel;
use App\Item\Infrastructure\Database\Repository\EloquentItemRepository;
use App\Listing\Domain\ListingRepositoryInterface;
use App\Listing\Infrastructure\Database\Model\ListingModel;
use App\Listing\Infrastructure\Database\Repository\EloquentListingRepository;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\UuidFactory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UuidFactoryInterface::class, function () {
            return new RamseyUuidFactory(new UuidFactory());
        });

        $this->app->bind(ListingRepositoryInterface::class, function () {
            /** @var ListingModel $listing */
            $listing = $this->app->get(ListingModel::class);

            return new EloquentListingRepository($listing);
        });

        $this->app->bind(ItemRepositoryInterface::class, function () {
            /** @var ItemModel $item */
            $item = $this->app->get(ItemModel::class);

            return new EloquentItemRepository($item);
        });
    }

    public function boot(): void
    {
    }
}
