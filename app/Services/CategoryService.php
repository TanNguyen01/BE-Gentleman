<?php

namespace App\Services;


use App\Models\Category;
use App\Models\Product;
use App\Traits\APIResponse;

class CategoryService
{

     use APIResponse;
     public function getAllCategory()
     {

          $categories = Category::all();

          foreach ($categories as $category) {

               $productCount = Product::where('category_id', $category->id)->count();

               $category->update(['quantity' => $productCount]);
          }

          return $categories;
     }

     public function getCategoryById($id)
     {

          return Category::find($id);
     }

     public function createCategory($data)
     {
          return Category::create($data);
     }

     public function updateCategory($id, $data)
     {
          return Category::find($id);
     }

     public function deleteCategory($id)
     {

          return Category::find($id);
     }
}
