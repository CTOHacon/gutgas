<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    public const ROLES = [
        'super',
        'admin',
        'user',
    ];
    public const PERMISSIONS = [
        '*',

        'admin.*',

        'admin.home',

        'admin.media.index',

        'admin.user.index',
        'admin.user.manage',

        'admin.mediaFile.index',
        'admin.mediaFile.manage',

        'admin.category.index',
        'admin.category.manage',

        'admin.product.index',
        'admin.product.manage',

        'admin.productpage.indexAdmin',
        'admin.productPage.manage',

        'admin.productFilter.index',
        'admin.productFilter.manage',

        'admin.productFilterValue.index',
        'admin.productFilterValue.manage',

        'admin.attribute.index',
        'admin.attribute.manage',

        'admin.attributeGroup.index',
        'admin.attributeGroup.manage',

        'admin.seoEntity.index',
        'admin.seoEntity.manage',

        'admin.page.indexAdmin',
        'admin.page.manage',

        'admin.globalSettings.index',
        'admin.globalSettings.manage',

        'admin.productsGroup.index',
        'admin.productsGroup.manage',

        'admin.requiredProductsGroup.index',
        'admin.requiredProductsGroup.manage',

        'admin.order.index',
        'admin.order.manage',

        'ajax.*',

        'ajax.mediaFile.index',
        'ajax.mediaFile.manage',
    ];
    public const ROLE_PERMISSIONS = [
        'admin' => [
            'admin.*',
            'ajax.*',
        ],
        'user' => [],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super') ? true : null;
        });
    }
}
