<?php

namespace App\Imports;

use App\Models\asset\Asset;
use App\Models\asset\AssetDetail;
use App\Models\asset\AssetType;
use App\Models\asset\AssetTypeDetail;
use App\Models\asset\AssetWarehouse;
use App\Models\shared\Vendor;
use App\Repositories\DocumentSerials;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetItemImport implements ToCollection, WithValidation
{
    public function rules(): array
    {
        return [
            'tên tài sản' => [
                'required' => 'Tên tài sản không được bỏ trống.',
            ],
            'model tài sản' => [
                'required' => 'Model Tài sản không được bỏ trống.',
            ],
            'số seri' => [
                'required' => 'Số seri không được bỏ trống.',
            ],
            'kho' => [
                'required' => 'Kho không được bỏ trống.',
            ],
        ];
    }
    public function collection(Collection $rows)
    {
        $header = $rows->shift()->toArray();
        $header = array_map(function ($item) {
            return mb_convert_case($item, MB_CASE_LOWER, "UTF-8");
        }, $header);
        $assetTypes = [];
        // Danh sách cột cố định
        $fixedColumns = ['sap code', 'model tài sản', 'số seri', 'tên tài sản', 'kho', 'nhà cung cấp', 'hashtag', 'giá mua', 'nhà sản xuất', 'nội dung', 'ngày nhập', 'ngày hết hạn sử dụng'];

        $newColumns = array_diff($header, $fixedColumns);

        $dataExcel = $rows->skip(0);
        $role = User::find(auth()->user()->id);

        foreach ($dataExcel as $key => $row) {
            $row = $row->map(function ($item) {
                return trim($item);
            });
            // Kiểm tra trùng lặp số seri và model tài sản
            $seri = $row[array_search('số seri', $header)];

            $model = $row[array_search('model tài sản', $header)];
            $seriModelPair = $seri . $model;

            if (isset($processedPairs[$seriModelPair])) {

                throw new \Exception("Dòng số '" . ($key + 1) . "': Số seri '{$seri}' và model tài sản '{$model}' đã xuất hiện trước đó trong file Excel.");
            }
            $processedPairs[$seriModelPair] = true;
            $duplicateType = AssetType::where('name', $model)->first();
            if ($duplicateType) {
                $existingAsset = Asset::where('seri', $seri)->where('asset_type_id', $duplicateType->id)->first();
                if ($existingAsset) {
                    throw new \Exception("Dòng số '" . ($key + 1) . "': Số seri '{$seri}' và model tài sản '{$model}' đã tồn tại trong hệ thống.");
                }
            }

            $assetData = new Asset();
            $assetData->create_by = $role->id;
            $assetData->quantity = 1;
            $assetData->waiting = 0;
            foreach ($header as $rowNumber => $column) {
                if ($column == 'sap code') {
                    if ($row[$rowNumber] == "") {
                        $assetData->sap_code = null;
                    } else {
                        $assetData->sap_code = $row[$rowNumber];
                    }

                }
                if ($column == 'model tài sản') {
                    $assetTypeData = AssetType::where('name', $row[$rowNumber])->first();
                    if ($assetTypeData) {
                        $assetData->asset_type_id = $assetTypeData->id;
                        $assetData->asset_group_id = $assetTypeData->asset_group_id;
                        $assetData->asset_status_id = 1;
                    } else {

                        throw new \Exception("  Cột số '" . ($rowNumber + 1) . "': Model tài sản '{$row[$rowNumber]}' chưa có trong hệ thống.");
                    }

                }
                if ($column == 'số seri') {
                    $assetData->seri = $row[$rowNumber];
                }
                if ($column == 'tên tài sản') {
                    $assetData->name = $row[$rowNumber];
                }
                if ($column == 'kho') {
                    $assetWarehouseData = AssetWarehouse::where('name', $row[$rowNumber])->first();
                    if ($assetWarehouseData) {
                        $serial_num = DocumentSerials::getSerial(Ultils::$MODULE_ASSET, 'TSAN', $assetWarehouseData->company_id, Ultils::$MODULE_FORMAT_SERIAL_NUMBER, true);
                        $assetData->asset_warehouse_id = $assetWarehouseData->id;
                        $assetData->asset_code = $serial_num;
                    } else {
                        throw new \Exception(" Cột số '" . ($rowNumber + 1) . "': Kho '{$row[$rowNumber]}' chưa có trong hệ thống.");
                    }
                }
                if ($column == 'nhà cung cấp') {
                    $assetVendor = Vendor::where('comp_name', $row[$rowNumber])->first();
                    if ($assetVendor) {
                        $assetData->vendor_id = $assetVendor->id;
                    }
                }
                if ($column == 'hashtag') {

                    if ($row[$rowNumber] == "") {
                        $assetData->hashtag = null;
                    } else {
                        $assetData->hashtag = $row[$rowNumber];
                    }

                }
                if ($column == 'giá mua') {
                    if ($row[$rowNumber] == "") {
                        $assetData->amount = null;
                    } else {
                        $assetData->amount = $row[$rowNumber];
                    }
                }
                if ($column == 'nhà sản xuất') {
                    if ($row[$rowNumber] == "") {
                        $assetData->producer = null;
                    } else {
                        $assetData->producer = $row[$rowNumber];
                    }
                }
                if ($column == 'nội dung') {
                    if ($row[$rowNumber] == "") {
                        $assetData->description = null;
                    } else {
                        $assetData->description = $row[$rowNumber];
                    }
                }
                if ($column == 'ngày nhập') {
                    if ($row[$rowNumber] == "") {
                        $assetData->add_date = null;
                    } else {
                        $assetData->add_date = $row[$rowNumber];
                    }

                }
                if ($column == 'ngày hết hạn sử dụng') {
                    if ($row[$rowNumber] == "") {
                        $assetData->end_date = null;
                    } else {
                        $assetData->end_date = $row[$rowNumber];
                    }

                }
            }
            $assetTypes[] = $assetData;
        }
        $rowIndex = 0;
        foreach ($assetTypes as $assetData) {

            $assetData->save();
            if ($assetData) {
                foreach ($newColumns as $columnn) {

                    $assetTypeDetail = AssetTypeDetail::where('asset_type_id', $assetData->asset_type_id)
                        ->where('name', $columnn)
                        ->first();
                    if ($assetTypeDetail && $columnn) {
                        $assetAssetDetails = new AssetDetail();
                        $assetAssetDetails->objectable_id = $assetData->id;
                        $assetAssetDetails->objectable_type = Asset::class;
                        $assetAssetDetails->name = $columnn;
                        // Lấy giá trị value động dựa trên tên cột
                        
                        if ($rowIndex < count($rows)) {
                            $assetAssetDetails->value = $rows[$rowIndex][array_search($columnn, $header)] ?? null;
                        } else {
                            $assetAssetDetails->value = null;
                        }
                        $assetAssetDetails->asset_type_id = $assetData->asset_type_id;
                     
                        $assetAssetDetails->save();
                    }
                }
            }
            $rowIndex++;

        }

    }
}
