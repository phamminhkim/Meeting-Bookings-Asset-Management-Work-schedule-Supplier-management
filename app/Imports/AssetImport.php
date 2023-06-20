<?php

namespace App\Imports;

use App\Models\asset\AssetGroup;
use App\Models\asset\AssetType;
use App\Models\asset\AssetTypeDetail;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetImport implements ToCollection, WithValidation
{

    public function rules(): array
    {
        return [
            'Mã model tài sản' => [
                'required' => 'Mã model tài sản không được bỏ trống.',
                'unique:asset_types,code' => 'Mã model tài sản đã tồn tại.',
            ],
            'Tên Model Tài sản' => [
                'required' => 'Tên Model Tài sản không được bỏ trống.',
            ],
            'Nhóm tài sản' => [
                'required' => 'Nhóm tài sản không được bỏ trống.',
            ],
            'Loại' => [
                'required' => 'Loại không được bỏ trống.',
            ],
        ];
    }
    public function collection(Collection $rows)
    {

        $header = $rows->shift()->toArray();
        $header = array_map(function ($item) {
            return mb_convert_case($item, MB_CASE_LOWER, "UTF-8");
        }, $header);
        $dataExcel = $rows->skip(0);
        
        $assetTypes = [];
        // Danh sách cột cố định
        $fixedColumns = ['mã model tài sản', 'tên model tài sản', 'nhóm tài sản', 'ghi chú', 'loại'];

        $newColumns = array_diff($header, $fixedColumns);
        $role = User::find(auth()->user()->id);

        foreach ($dataExcel as $key => $row) {
            $row = $row->map(function ($item) {
                return trim($item);
            });

            $assetTypeData = new AssetType();
            $assetTypeData->create_by = $role->id;

            foreach ($header as $rowNumber => $column) {

                if ($column == 'mã model tài sản') {
                    // Kiểm tra trùng lặp trong database
                    $isDuplicate = AssetType::where('code', $row[$rowNumber])->exists();
                    if ($isDuplicate) {
                        // $validator->errors()->add('code', 'Mã model tài sản "'.$row[$rowNumber].'" đã tồn tại trong database.');
                        throw new \Exception("Dòng số '" . ($rowNumber + 1) . "': Mã model tài sản '{$row[$rowNumber]}' đã tồn tại trong database.");
                       
                    }
                    $assetTypeData->code = $row[$rowNumber];
                }
                if ($column == 'tên model tài sản') {
                    $dep_temp = AssetType::where('create_by', $role->id)->where('name', $row[$rowNumber])->first();
                    // dd($dep_temp);
                    if ($dep_temp) {
                        throw new \Exception("Tên model tài sản '{$row[$rowNumber]}' đã được '{$role->name}' tạo");
                    }
                    $assetTypeData->name = $row[$rowNumber];
                }
                if ($column == 'nhóm tài sản') {

                    $assetGroupData = AssetGroup::where('name', $row[$rowNumber])->first();
                    if ($assetGroupData) {
                        $assetTypeData->asset_group_id = $assetGroupData->id;
                    } else {
                        throw new \Exception("Dòng số '" . ($rowNumber + 1) . "': Nhóm tài sản '{$row[$rowNumber]}' không có trong hệ thống.");
                    }
                }
                if ($column == 'ghi chú') {
                    $assetTypeData->description = $row[$rowNumber];
                }
                if ($column == 'loại') {
                    $type = $row[$rowNumber];
                    if (in_array($type, ["Tài sản", "Tai san", "tai san", "tài sản"])) {
                        $assetTypeData->type = 0;
                    } else if (in_array($type, ["Công cụ dụng cụ", "công cụ dụng cụ", "cong cu dung cu", "Cong cu dung cu"])) {
                        $assetTypeData->type = 1;
                    } else {
                        // Invalid value
                        throw new \Exception("Loại chỉ bao gồm Tài sản hoặc Công cụ dụng cụ. Lỗi sai ở:  '{$row[$rowNumber]}' ");
                    }
                }

            }
            $assetTypes[] = $assetTypeData;

        }
        // Lưu AssetType
        foreach ($assetTypes as $assetTypeData) {
            $assetTypeData->save();
            foreach ($newColumns as $column) {
                $assetTypeDetail = new AssetTypeDetail();
                $assetTypeDetail->name = $column;
                $assetTypeDetail->value = $row[array_search($column, $header)];
                $assetTypeDetail->asset_type_id = $assetTypeData->id;
                $assetTypeDetail->save();
            }
        }

    }
}
