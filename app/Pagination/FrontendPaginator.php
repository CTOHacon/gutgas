<?php
namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

class FrontendPaginator extends LengthAwarePaginator
{
    public function url($page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        $url = preg_replace('/\?page=\d+/', '', $this->path());
        $url = preg_replace('/\/page-\d+/', '', $url);
        $url = rtrim($url, '/');
        $url = $url . '/page-' . $page;
        $url = preg_replace('/\/page-1/', '', $url);
        if (count(request()->query())) {
            $url = $url . '?' . http_build_query(request()->query());
        }
        return $url;
    }
}
