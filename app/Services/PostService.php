<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

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
        $comment = $this->showPost($id);
        if (Auth::id() !== $comment->user_id && Auth::user()->role_id == 1 ) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $this->eloquentUpdate($id, $data);
    }

    public function destroyPost($id)
    {
        $comment = $this->showPost($id);
        if (Auth::id() !== $comment->user_id && Auth::user()->role_id == 1 ) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $this->eloquentDelete($id);
    }
}
