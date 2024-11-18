<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait MetableExtention
{
    protected static function bootMetableExtention()
    {
        self::deleting(function ($instance) {
            $instance->meta()->delete();
        });
    }

    public function setMetaFromRequest(Request $request)
    {
        $request->validate([
            'meta' => 'array',
            'translatableMetaKeys' => 'array',
        ]);
        $locale = app()->getLocale();

        foreach ($request->meta as $key => $value) {
            if (in_array($key, $request->translatableMetaKeys)) {
                $this->setMeta($key . '_' . $locale, $value);
                continue;
            }
            $this->setMeta($key, $value);
        }
    }
}
