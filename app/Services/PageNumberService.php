<?php
namespace App\Services;

class PageNumberService
{
    public function __construct()
    {
    }
    public static function getPageNumber()
    {
        if (preg_match('/\/page-\d+$/', request()->path())) {
            $pageNumber = (int) preg_replace('/^.*page-(\d+)$/', '$1', request()->path());
            return $pageNumber;
        } else {
            return 1;
        }
    }
}