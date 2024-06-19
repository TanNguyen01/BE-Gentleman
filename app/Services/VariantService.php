<?php

namespace App\Services;

use App\Models\Variant;
use App\Traits\APIResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VariantService extends AbstractServices
{
    use APIResponse;


    public function __construct(Variant $variant)
    {
        Parent::__construct($variant);
    }

    public function getVariant()
    {
        return $this->eloquentGetAll();
    }

    public function showVariant($id)
    {
        // L?y thông tin bi?n th? cùng v?i các thu?c tính c?a nó
        $variant = Variant::with('attributes')->find($id);



        // Tr? v? k?t qu?
        return $variant;
    }

    public function storeVariant(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image_path'] = $this->uploadFile($data['image'], 'variants');
        }
        $variant = $this->eloquentPostCreate($data);
        return $variant;
    }

    public function updateVariant($id, array $data): ?Variant
    {
        $variant = $this->model->find($id);
        if ($variant) {
            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $data['file_path'] = $this->uploadFile($data['file'], 'variants');
                // Xóa file c? n?u c?n thi?t
                if ($variant->file_path) {
                    Storage::delete($variant->file_path);
                }
            }
            $variant->update($data);
        }
        return $variant;
    }
    public function destroyVariant($id)
    {
        return $this->multiDelete($id);
    }
}
