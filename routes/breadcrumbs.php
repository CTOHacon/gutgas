<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.home'), route('home'));
});

Breadcrumbs::for('login', function (BreadcrumbTrail $trail) {
    $trail->push('Login', route('login'));
});
Breadcrumbs::for('logout', function (BreadcrumbTrail $trail) {
    $trail->push('Logout', route('logout'));
});

Breadcrumbs::for('product.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.catalog'), route('product.index'));
});
Breadcrumbs::for('product.show', function (BreadcrumbTrail $trail, $args) {
    $trail->parent('product.index');
    $trail->push($args['product']->name, route('product.show', $args['product']->id));
});

// ADMIN
Breadcrumbs::for('admin.home', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.home'), route('admin.home'));
});

// USER
Breadcrumbs::for('admin.user.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.home');
    $trail->push('Users', route('admin.user.index'));
});
Breadcrumbs::for('admin.user.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.user.index');
    $trail->push('Create User', route('admin.user.create'));
});
Breadcrumbs::for('admin.user.edit', function (BreadcrumbTrail $trail, $args) {
    $trail->parent('admin.user.index');
    $trail->push('Edit User ' . $args['user']->name, route('admin.user.edit', $args['user']->id));
});

Breadcrumbs::for('admin.mediaFile.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.home');
    $trail->push('Media Files', route('admin.mediaFile.index'));
});


// CATEGORY
Breadcrumbs::for('admin.category.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.home');
    $trail->push('Categories', route('admin.category.index'));
});

Breadcrumbs::for('admin.category.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.category.index');
    $trail->push('Create Category', route('admin.category.create'));
});

Breadcrumbs::for('admin.category.edit', function (BreadcrumbTrail $trail, $args) {
    $trail->parent('admin.category.index');
    $trail->push('Edit Category ' . $args['category']->name, route('admin.category.edit', $args['category']->id));
});



// PRODUCT
Breadcrumbs::for('admin.product.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.home');
    $trail->push('Products', route('admin.product.index'));
});

Breadcrumbs::for('admin.product.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.product.index');
    $trail->push('Create Product', route('admin.product.create'));
});

Breadcrumbs::for('admin.product.edit', function (BreadcrumbTrail $trail, $args) {
    $trail->parent('admin.product.index');
    $trail->push('Edit Product ' . $args['product']->name, route('admin.product.edit', $args['product']->id));
});


// admin.productPage
Breadcrumbs::for('admin.productPage.edit', function (BreadcrumbTrail $trail, $args) {
    $trail->parent('admin.product.index');
    $trail->push('Edit Product Page ' . $args['product']->name, route('admin.productPage.edit', $args['product']->id));
});
