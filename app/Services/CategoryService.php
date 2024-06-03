<?php

namespace App\Services;


 use App\Models\Category;
 use App\Traits\APIResponse;

 class CategoryService {

       use APIResponse;
       public function getAllCategory(){

              return Category::query()->get();
       }

       public function getCategoryById($id){

            return Category::find($id);
       }

       public function createCategory($data){
            return Category::create($data);
       }

       public function updateCategory($id,$data){
             return Category::find($id);
       }

       public function deleteCategory($id){

            return Category::find($id);
       }
 }
