<?php

namespace App\Infrastructure\Providers;

use App\Domain\Common\Uuid\UuidFactoryInterface;
use App\Domain\Item\ItemRepositoryInterface;
use App\Domain\Listing\ListingRepositoryInterface;
use App\Infrastructure\Common\Uuid\RamseyUuidFactory;
use App\Infrastructure\Database\Item\EloquentItemRepository;
use App\Infrastructure\Database\Item\ItemModel;
use App\Infrastructure\Database\Listing\EloquentListingRepository;
use App\Infrastructure\Database\Listing\ListingModel;
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
            /** @var \App\Infrastructure\Database\Item\ItemModel $item */
            $item = $this->app->get(ItemModel::class);

            return new EloquentItemRepository($item);
        });
    }

    public function boot(): void
    {
    }
}
