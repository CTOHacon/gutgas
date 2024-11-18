<?php

use Illuminate\Support\Facades\DB;

$records = DB::table('scaled_media_files')->get();

foreach ($records as $record) {
    $newValue = str_replace('storage', '/storage', $record->url);

    DB::table('scaled_media_files')
        ->where('id', $record->id)
        ->update([
            'url' => $newValue,
        ]);
}

exit;
