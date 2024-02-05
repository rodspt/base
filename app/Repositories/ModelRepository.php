<?php

namespace App\Repositories;



class ModelRepository
{


    public function paginationFilter($query, $filters)
    {
        if(count($filters) === 0){
            return $query;
        }

        foreach($filters as $campo => $filter):
             if(is_string($campo) && $this->filterValidate($filter)) {
                $mathMode = $filter["matchMode"];
                $this->$mathMode($query, $campo, $filter["value"]);
             }
        endforeach;
        return $query;
    }

    public function filterValidate($filter): bool
    {
        $arMatchMode = array("equals","notEquals","contains","notContains","startsWith","endsWith");
        if($filter["value"] && $filter["matchMode"] && in_array($filter["matchMode"],$arMatchMode)){
              return true;
        }
        return false;
    }

    public function equals($query, $name, $value)
    {
        if(is_string($value)){
            $query->where($name, $value);

        }else{
            $query->whereIn($name, $value);
        }
    }

    public function notEquals($query, $name, $value){
         $query->where($name,'<>', $value);
    }
    public function contains($query, $name, $value){
         $query->where($name,'ILIKE','%'.$value.'%');
    }
    public function notContains($query, $name, $value){
         $query->where($name,'NOT ILIKE','%'.$value.'%');
    }
    public function startsWith($query, $name, $value){
         $query->where($name,'ILIKE', $value.'%');
    }
    public function endsWith($query, $name, $value){
         $query->where($name,'ILIKE','%'.$value);
    }
}
