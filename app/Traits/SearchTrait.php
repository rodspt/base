<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait SearchTrait
{

    public function vazio($search = null)
    {
        return is_null($search) ||
               trim($search) === "" ||
               trim($search) === "{search}";
    }

    public function filtroSearch($search = null)
    {
        return $this->vazio($search)? null : $search;
    }

    public function cacheSearch($classe,  $perPage, $page,  $search = null)
    {
        $search2 = $this->vazio($search)? "":  "_".strip_tags($search);
        $nameCache = trim(class_basename($classe)."_search_".$page."_".$perPage.$search2);
        return $nameCache;
    }




}
