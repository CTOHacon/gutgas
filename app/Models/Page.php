<?php

namespace App\Models;

use App\Http\Controllers\Pages\ContactsPageController;
use App\Http\Controllers\Pages\HomePageController;
use App\Http\Controllers\Pages\ProductsCatalogPageController;
use App\Http\Controllers\Pages\SimplePageController;
use App\Http\Controllers\Pages\PageWithSidebarSliderController;
use App\Traits\MetableExtention;
use App\Traits\Seoable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Zoha\Metable;

class Page extends Model
{
    use HasFactory, Metable, MetableExtention, HasTranslations, Seoable;

    protected $metaTable = 'page_meta';

    protected $guarded = [];

    public const PAGE_TYPES = [
        'SimplePage' => SimplePageController::class,
        'HomePage' => HomePageController::class,
        'ProductsCatalogPage' => ProductsCatalogPageController::class,
        'PageWithSidebarSlider' => PageWithSidebarSliderController::class,
        'ContactsPage' => ContactsPageController::class,
    ];

    public $translatable = ['title', 'content'];

    protected $with = ['meta', 'seoEntity'];

    public function seoController()
    {
        if (isset(self::PAGE_TYPES[$this->type])) {
            return self::PAGE_TYPES[$this->type];
        }
        return null;
    }
}
