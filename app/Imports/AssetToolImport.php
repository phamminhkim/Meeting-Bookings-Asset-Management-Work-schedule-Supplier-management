<?php

namespace App\Imports;

use App\Models\asset\AssetGroup;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetType;
use App\Models\asset\AssetDetail;
use App\Models\asset\AssetTypeDetail;
use App\Models\asset\AssetWarehouse;
use App\Repositories\DocumentSerials;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AssetToolImport implements ToCollection
{

    public function collection(Collection $rows)
    {

        $header = $rows->shift()->toArray();
        $header = array_map(function ($item) {
            return mb_convert_case($item, MB_CASE_LOWER, "UTF-8");
        }, $header);
        $dataExcel = $rows->skip(0);

        $assetTypes = [];
        // Danh sách cột cố định
        $fixedColumns = ["model tài sản", "tên công cụ dụng cụ", "nhóm", "kho", 'nhà cung cấp', 'hashtag', 'nhà sản xuất', 'giá mua trên một đơn vị', 'số lượng', 'sap code', 'ngày nhập', 'nội dung'];

        $newColumns = array_diff($header, $fixedColumns);
        $role = User::find(auth()->user()->id);
        foreach ($dataExcel as $key => $row) {
            $row = $row->map(function ($item) {
                return trim($item);
            });
            $asset_tool = new AssetTool();
            $asset_tool->create_by = $role->id;
            $asset_tool->asset_status_id = 1;

            $asset_detail = new AssetTypeDetail();

            foreach ($header as $rowNumber => $column) {
                if ($column == 'model tài sản') {
                    $asset_type = AssetType::where('name', $row[$rowNumber])->first();
                    if ($asset_type) {
                        $asset_tool->asset_type_id = $asset_type->id;
                       
                    } else {
                        throw new \Exception('Model tài sản ' . $row[$rowNumber] . ' không tồn tại');
                    }
                } else
                if ($column == 'tên công cụ dụng cụ') {
                    $asset_tool->name = $row[$rowNumber];
                } else
                if ($column == 'nhóm') {
                    $asset_group = AssetGroup::where('name', $row[$rowNumber])->first();
                    if ($asset_group) {
                        $asset_tool->asset_group_id = $asset_group->id;
                    } else {
                        throw new \Exception('Nhóm không tồn tại');
                    }
                } else
                if ($column == 'kho') {
                    $warehouse = AssetWarehouse::where('name', $row[$rowNumber])->first();
                    if ($warehouse) {
                        $serial_num = DocumentSerials::getSerial(Ultils::$MODULE_ASSET, 'CCDC', $warehouse->company_id, Ultils::$MODULE_FORMAT_SERIAL_NUMBER, true);
                        $asset_tool->asset_warehouse_id = $warehouse->id;
                        $asset_tool->asset_code = $serial_num;
                    } else {
                        throw new \Exception(" Cột số '" . ($rowNumber + 1) . "': Kho '{$row[$rowNumber]}' chưa có trong hệ thống.");
                    }

                } else
                if ($column == 'nhà cung cấp') {

                } else
                if ($column == 'hashtag') {
                    if ($row[$rowNumber] == "") {
                        $asset_tool->hashtag = null;
                    } else {
                        $asset_tool->hashtag = $row[$rowNumber];
                    }
                } else
                if ($column == 'nhà sản xuất') {

                } else
                if ($column == 'giá mua trên một đơn vị') {
                    if ($row[$rowNumber] == "") {
                        $asset_tool->amount = null;
                    } else {
                        $asset_tool->amount = $row[$rowNumber];
                    }
                } else
                if ($column == 'số lượng') {
                    $asset_tool->quantity = $row[$rowNumber];
                    $asset_tool->sloc_tool = $row[$rowNumber];
                } else
                if ($column == 'sap code') {
                    if ($row[$rowNumber] == "") {
                        $asset_tool->sap_code = null;
                    } else {
                        $asset_tool->sap_code = $row[$rowNumber];
                    }
                } else
                if ($column == 'ngày nhập') {
                    if ($row[$rowNumber] == "") {
                        $asset_tool->add_date = null;
                    } else {
                        $asset_tool->add_date = $row[$rowNumber];
                    }
                } else
                if ($column == 'nội dung') {
                    if ($row[$rowNumber] == "") {
                        $asset_tool->description = null;
                    } else {
                        $asset_tool->description = $row[$rowNumber];
                    }
                }
            }
            $assetTypes[] = $asset_tool;
        }
        $rowIndex = 0;
        foreach ($assetTypes as $asset_tool) {
            $asset_tool->save();
            if ($asset_tool) {
                foreach ($newColumns as $columnn) {
                    $assetTypeDetail = AssetTypeDetail::where('asset_type_id', $asset_tool->asset_type_id)
                        ->where('name', $columnn)
                        ->first();
                    if ($assetTypeDetail && $columnn) {
                        $assetAssetDetails = new AssetDetail();
                        $assetAssetDetails->objectable_id = $asset_tool->id;
                        $assetAssetDetails->objectable_type = AssetTool::class;
                        $assetAssetDetails->name = $columnn;
                        // Lấy giá trị value động dựa trên tên cột
                        
                        if ($rowIndex < count($rows)) {
                            $assetAssetDetails->value = $rows[$rowIndex][array_search($columnn, $header)] ?? null;
                        } else {
                            $assetAssetDetails->value = null;
                        }
                        $assetAssetDetails->asset_type_id = $asset_tool->asset_type_id;
                     
                        $assetAssetDetails->save();
                    }
                }
            }
            $rowIndex++;
        }

    }
}
