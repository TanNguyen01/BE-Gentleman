<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

abstract class AbstractServices
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function eloquentGetAll(): Collection
    {
        return $this->model->all();
    }

    public function eloquentFind(int $id): ?Model
    {
        return $this->model->findOrFail($id);
                // public function find(int $id): ?User
                // {
                //     return $this->find($id);
                // }
    }

    public function  eloquentPostCreate(array $data): Model
    {
        return $this->model->create($data);
    }

    public function eloquentUpdate(int $id, array $data): ?Model
    {
        $record = $this->eloquentFind($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    public function eloquentDelete(int $id): bool
    {
        $record = $this->eloquentFind($id);
        return $record->delete();
    }

    public function eloquentWhere($key,$value){
        $record = $this->model::where($key, $value)
                     ->orderBy('updated_at', 'desc')
                     ->get();
        return $record;
    }

    public function eloquentMultiWhere(array $fill){
        // $fill = [
        //   'user_id' => 1,
        //   'status' => 'Pending'
        // ];
        $table = $this->model->getTable();
        foreach ($fill as $fillKey => $fillValue) {
            $conditions[] = $fillKey. ' = ?';
            $values[] = $fillValue;
        }
        $conditionString = implode(' AND ', $conditions);
        $res = DB::table($table)
        ->whereRaw($conditionString, $values)
        ->orderByDesc('updated_at')
        ->get();
        return $res;
    }
// xoa nheu ban ghi
     public function eloquentMultiDelete(array $ids): int
     {
        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                $this->model->destroy($id);
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
     }

 // them nhieu ban ghi
     public function eloquentMutiInsert(array $data): bool
     {
        //   $data = [
        //          ['name' => 'John', 'email' => 'john@example.com'],
        //          ['name' => 'Jane', 'email' => 'jane@example.com'],
        //          ['name' => 'Alice', 'email' => 'alice@example.com'],
        //      ];

        DB::beginTransaction();

        try {
            foreach ($data['data'] as $value) {
                $this->model->insert($value);
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
     }

// lay du lieu theo quan he
    public function eloquentWithRelations($id, array $relations): ?Model
    {
        $record = $this->model->with($relations)->findOrFail($id);
        return $record;
    }
                // public function getUserWithPosts(int $userId): ?User
                // {
                //     return $this->withRelations($userId, ['posts']);
                // }

// luu file
    public function uploadFile(UploadedFile $file, string $path): string
    {
        $filePath = $file->store($path);
        return $filePath;
    }

// xoa file
    public function deleteFile(string $filePath): bool
    {
        return Storage::delete($filePath);
    }

// lay file url
    public function getFileUrl(string $filePath): string
    {
        return Storage::url($filePath);
    }

//  xoa nhieu file storage
    public function multiDelete(array $filePaths): bool
    {
        $success = true;

        foreach ($filePaths as $filePath) {
            $deleted = Storage::delete($filePath);
            if (!$deleted) {
                $success = false;
            }
        }
        return $success;
    }

    public function search(array $criteria): Collection
    {
        $query = $this->model->query();

        foreach ($criteria as $column => $value) {
            if (is_array($value)) {
                // N?u giá tr? là m?t m?ng, s? d?ng whereIn
                $query->whereIn($column, $value);
            } else {
                // N?u không, s? d?ng where
                $query->where($column, 'like', '%' . $value . '%');
            }
        }

        return $query->get();
    }
}
