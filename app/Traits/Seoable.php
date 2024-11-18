<?php

namespace App\Traits;

use App\Models\SeoEntity;

trait Seoable
{
    protected static function bootSeoable()
    {
        self::created(function ($instance) {

            $controller = '';
            if (method_exists($instance, 'seoController')) {
                $controller = $instance->seoController();
            } else {
                throw new \Exception('seoController method not found in ' . get_class($instance));
            }

            $method = 'show';
            if (method_exists($instance, 'seoAction')) {
                $method = $instance->seoAction();
            }

            $instance->seoEntity()->create([
                'controller' => $controller,
                'action' => $method,
            ]);
        });

        self::deleting(function ($instance) {
            $instance->seoEntity()->delete();
        });
    }

    public function seoEntity()
    {
        return $this->morphOne(SeoEntity::class, 'seo_entiteable');
    }
}
