<?php

namespace App\Services;

use App\Models\Post;

class PostService extends AbstractServices
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function getAllPost()
    {
        return $this->eloquentGetAll();
    }

    public function storePost($data)
    {
        return $this->eloquentPostCreate($data);
    }

    public function showPost($id)
    {
        return $this->eloquentFind($id);
    }

    public function updatePost($id, $data)
    {
        return $this->eloquentUpdate($id, $data);
    }

    public function destroyPost($id)
    {
        return $this->eloquentDelete($id);
    }
}
