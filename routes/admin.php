<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeGroupController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GlobalSettingsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\MediaFileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductFilterController;
use App\Http\Controllers\Admin\ProductFilterValueController;
use App\Http\Controllers\Admin\ProductPageController;
use App\Http\Controllers\Admin\SeoEntityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductsGroupController;
use App\Http\Controllers\RequiredProductsGroupController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
], function () {
    Route::group([
        'middleware' => ['auth', 'sessionLocaleHandler'],
        'as' => 'admin.'
    ], function () {
        Route::get('/', HomeController::class)->name('home')->middleware('permission:admin.home');

        Route::get('/set-locale/{language}', function (string $language) {
            Session()->put('locale', $language);

            return redirect()->back();
        })->name('set-locale');

        Route::get('/media', HomeController::class)->name('media.index')->middleware('permission:admin.media.index');

        Route::controller(UserController::class)->prefix('user')->as('user.')
            ->group(function () {
                Route::get('', 'index')->name('index')->middleware('permission:admin.user.index');

                Route::get('/create', 'create')->name('create')->middleware('permission:admin.user.manage');
                Route::post('/store', 'store')->name('store')->middleware('permission:admin.user.manage');

                Route::delete('/delete/{user}', 'destroy')->name('destroy')->middleware('permission:admin.user.manage');

                Route::get('{user}', 'edit')->name('edit')->middleware('permission:admin.user.manage');
                Route::put('{user}', 'update')->name('update')->middleware('permission:admin.user.manage');
            });

        Route::controller(MediaFileController::class)->prefix('mediaFile')->as('mediaFile.')
            ->group(
                function () {
                    Route::get('', 'index')->name('index')->middleware('permission:admin.mediaFile.index');
                }
            );

        // CategoryController
        Route::controller(CategoryController::class)->prefix('category')->as('category.')
            ->group(
                function () {
                    Route::get('', 'index')->name('index')->middleware('permission:admin.category.index');

                    Route::get('/create', 'create')->name('create')->middleware('permission:admin.category.manage');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.category.manage');

                    Route::delete('/delete/{category}', 'destroy')->name('destroy')->middleware('permission:admin.category.manage');

                    Route::get('{category}', 'edit')->name('edit')->middleware('permission:admin.category.manage');
                    Route::put('{category}', 'update')->name('update')->middleware('permission:admin.category.manage');
                }
            );

        // AttributeGroup Controller
        Route::controller(AttributeGroupController::class)->prefix('attributeGroup')->as('attributeGroup.')
            ->group(
                function () {
                    Route::get('', 'index')->name('index')->middleware('permission:admin.attributeGroup.index');

                    Route::get('/create', 'create')->name('create')->middleware('permission:admin.attributeGroup.manage');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.attributeGroup.manage');

                    Route::delete('/delete/{attributeGroup}', 'destroy')->name('destroy')->middleware('permission:admin.attributeGroup.manage');

                    Route::get('{attributeGroup}', 'edit')->name('edit')->middleware('permission:admin.attributeGroup.manage');
                    Route::put('{attributeGroup}', 'update')->name('update')->middleware('permission:admin.attributeGroup.manage');
                }
            );

        // Attribute Controller
        Route::controller(AttributeController::class)->prefix('attribute')->as('attribute.')
            ->group(
                function () {
                    // Route::get('', 'index')->name('index')->middleware('permission:admin.attribute.index');

                    Route::get('/create', 'create')->name('create')->middleware('permission:admin.attribute.manage');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.attribute.manage');

                    Route::delete('/delete/{attribute}', 'destroy')->name('destroy')->middleware('permission:admin.attribute.manage');

                    Route::get('{attribute}', 'edit')->name('edit')->middleware('permission:admin.attribute.manage');
                    Route::put('{attribute}', 'update')->name('update')->middleware('permission:admin.attribute.manage');
                }
            );

        Route::controller(ProductFilterController::class)->prefix('productFilter')->as('productFilter.')
            ->group(
                function () {
                    Route::get('', 'index')->name('index')->middleware('permission:admin.productFilter.index');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.productFilter.manage');
                    Route::delete('/delete/{productFilter}', 'destroy')->name('destroy')->middleware('permission:admin.productFilter.manage');
                    Route::put('{productFilter}', 'update')->name('update')->middleware('permission:admin.productFilter.manage');
                }
            );

        Route::controller(ProductFilterValueController::class)->prefix('productFilterValue')->as('productFilterValue.')
            ->group(
                function () {
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.productFilterValue.manage');
                    Route::delete('/delete/{productFilterValue}', 'destroy')->name('destroy')->middleware('permission:admin.productFilterValue.manage');
                    Route::put('{productFilterValue}', 'update')->name('update')->middleware('permission:admin.productFilterValue.manage');
                }
            );

        // Product Controller
        Route::controller(ProductController::class)->prefix('product')->as('product.')
            ->group(
                function () {
                    Route::get('', 'index')->name('index')->middleware('permission:admin.product.index');
                    Route::get('/search/{search}', 'index')->name('search')->middleware('permission:admin.product.index');

                    Route::get('/create', 'create')->name('create')->middleware('permission:admin.product.manage');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.product.manage');

                    Route::delete('/delete/{product}', 'destroy')->name('destroy')->middleware('permission:admin.product.manage');

                    Route::get('{product}', 'edit')->name('edit')->middleware('permission:admin.product.manage');
                    Route::put('{product}', 'update')->name('update')->middleware('permission:admin.product.manage');
                }
            );

        // ProductPage controller
        Route::controller(ProductPageController::class)->prefix('product/productPage')->as('productPage.')
            ->group(
                function () {
                    Route::get('', 'index')->name('index')->middleware('permission:admin.product.index');

                    Route::get('/create', 'create')->name('create')->middleware('permission:admin.product.manage');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.product.manage');

                    Route::get('{product}', 'edit')->name('edit')->middleware('permission:admin.product.manage');
                    Route::put('{productPage}', 'update')->name('update')->middleware('permission:admin.product.manage');

                    Route::delete('/delete/{productPage}', 'destroy')->name('destroy')->middleware('permission:admin.product.manage');
                }
            );

        // SeoEntityController
        Route::controller(SeoEntityController::class)->as('seoEntity.')
            ->group(
                function () {
                    // Route::get('', 'index')->name('index')->middleware('permission:admin.seoEntity.index');

                    // Route::get('/create', 'create')->name('create')->middleware('permission:admin.seoEntity.manage');
                    // Route::post('/store', 'store')->name('store')->middleware('permission:admin.seoEntity.manage');

                    // Route::delete('/delete/{seoEntity}', 'destroy')->name('destroy')->middleware('permission:admin.seoEntity.manage');

                    Route::get('product/productPage/seoEntity/{seoEntity}/', 'editForProductPage')->name('forProductPage.edit')->middleware('permission:admin.seoEntity.manage');
                    Route::get('page/seoEntity/{seoEntity}/', 'editForPage')->name('forPage.edit')->middleware('permission:admin.seoEntity.manage');
                    Route::get('productsGroup/seoEntity/{seoEntity}/', 'editForProductsGroup')->name('forProductsGroup.edit')->middleware('permission:admin.seoEntity.manage');

                    Route::get('seoEntity/{seoEntity}', 'edit')->name('edit')->middleware('permission:admin.seoEntity.manage');
                    Route::put('seoEntity/{seoEntity}', 'update')->name('update')->middleware('permission:admin.seoEntity.manage');
                }
            );

        // PageController
        Route::controller(PageController::class)->prefix('page')->as('page.')
            ->group(
                function () {
                    Route::get('', 'indexAdmin')->name('indexAdmin')->middleware('permission:admin.page.indexAdmin');
                    Route::get('/search/{search}', 'indexAdmin')->name('searchAdmin')->middleware('permission:admin.page.indexAdmin');

                    Route::get('/create', 'create')->name('create')->middleware('permission:admin.page.manage');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.page.manage');

                    Route::delete('/delete/{page}', 'destroy')->name('destroy')->middleware('permission:admin.page.manage');

                    Route::get('{page}', 'edit')->name('edit')->middleware('permission:admin.page.manage');
                    Route::put('{page}', 'update')->name('update')->middleware('permission:admin.page.manage');
                }
            );

        // GlobalSettingsController
        Route::controller(GlobalSettingsController::class)->prefix('globalSettings')->as('globalSettings.')
            ->group(
                function () {
                    Route::get('', 'index')->name('index')->middleware('permission:admin.globalSettings.index');
                    Route::put('/updateAll', 'updateAll')->name('updateAll')->middleware('permission:admin.globalSettings.manage');
                }
            );

        // ProductsGroupController
        Route::controller(ProductsGroupController::class)->prefix('productsGroup')->as('productsGroup.')
            ->group(
                function () {
                    Route::get('', 'indexAdmin')->name('indexAdmin')->middleware('permission:admin.productsGroup.indexAdmin');

                    Route::get('/create', 'create')->name('create')->middleware('permission:admin.productsGroup.manage');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.productsGroup.manage');

                    Route::delete('/delete/{productsGroup}', 'destroy')->name('destroy')->middleware('permission:admin.productsGroup.manage');

                    Route::get('{productsGroup}', 'edit')->name('edit')->middleware('permission:admin.productsGroup.manage');
                    Route::put('{productsGroup}', 'update')->name('update')->middleware('permission:admin.productsGroup.manage');
                }
            );

        Route::controller(RequiredProductsGroupController::class)->prefix('requiredProductsGroup')->as('requiredProductsGroup.')
            ->group(
                function () {
                    Route::get('', 'index')->name('index')->middleware('permission:admin.requiredProductsGroup.index');

                    Route::get('/create', 'create')->name('create')->middleware('permission:admin.requiredProductsGroup.manage');
                    Route::post('/store', 'store')->name('store')->middleware('permission:admin.requiredProductsGroup.manage');

                    Route::delete('/delete/{requiredProductsGroup}', 'destroy')->name('destroy')->middleware('permission:admin.requiredProductsGroup.manage');

                    Route::get('{requiredProductsGroup}', 'edit')->name('edit')->middleware('permission:admin.requiredProductsGroup.manage');
                    Route::put('{requiredProductsGroup}', 'update')->name('update')->middleware('permission:admin.requiredProductsGroup.manage');
                }
            );

        // Order
        Route::controller(OrderController::class)->prefix('order')->as('order.')
            ->group(
                function () {
                    Route::get('', 'indexAdmin')->name('index')->middleware('permission:admin.order.index');
                    Route::get('/search/{search}', 'indexAdmin')->name('search')->middleware('permission:admin.order.index');

                    Route::delete('/delete/{order}', 'destroy')->name('destroy')->middleware('permission:admin.order.manage');

                    Route::get('{order}', 'edit')->name('edit')->middleware('permission:admin.order.manage');
                    Route::put('{order}', 'update')->name('update')->middleware('permission:admin.order.manage');
                }
            );
    });
});
