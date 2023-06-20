<?php

namespace App\Imports;

use App\Models\asset\Asset;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\Models\asset\AssetUse;
use App\Models\shared\Department;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class AssetChangeItemImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $header = $rows->shift()->toArray();
        
        $header = array_map(function ($item) {
            return mb_convert_case($item, MB_CASE_LOWER, "UTF-8");
        }, $header);
        
        $assetTypes = [];
        $dataExcel = $rows->skip(0);
      
        $role = User::find(auth()->user()->id);
        foreach ($dataExcel as $key => $row) {
            $row = $row->map(function ($item) {
                return trim($item);
            });
          
            DB::beginTransaction();
            try {
              
                $assetTransactionData = new AssetTransaction();
                $assetTransactionData->create_by = $role->id;
               
                $assetTransactionData->created_at = null;
                $assetTransactionData->updated_at = null;
                $assetTransactionItem = new AssetTransactionItem();
               
                $company_id = null;
                $companyCode = "";
                $department_processed = false;
                $fakeDepartment = "";
                $user_use = "";
                $msnv = "";
                foreach ($header as $rowNumber => $column) {
                    if(count($header) == 7){
                        if ($column == 'loại giao dịch') {
                            if ($row[$rowNumber] == 'Bàn giao') {
                                $assetTransactionData->trans_type = 1;
                            } else {
                                // Thông báo lỗi nếu dữ liệu không phù hợp
                                throw new \Exception("Dòng số " . ($key + 1) . ": Nội dung loại giao dịch không đúng định dạng.");
                            }
                        }
                        if ($column == 'tên tài sản') {
                            $assetAsset = Asset::where('name', $row[$rowNumber])->first();
                            if ($assetAsset) {
                                // Kiểm tra xem Asset đã có user_id hay chưa
                                if ($assetAsset->user_id || $assetAsset->department_id) {
                                    throw new \Exception("Dòng số " . ($key + 1) . ": '{$row[$rowNumber]}' đã được bàn giao cho người sử dụng hoặc phòng ban.");
                                }
                                $assetTransactionItem->objectable_id = $assetAsset->id;
                                $assetTransactionItem->objectable_type = Asset::class;
                                $assetTransactionItem->quantity = $assetAsset->quantity;
                                $assetTransactionItem->asset_status_id = $assetAsset->asset_status_id;
                                $assetTransactionItem->asset_warehouse_id = $assetAsset->asset_warehouse_id;
                                $assetTransactionItem->quantity_sloc = 1;
                            } else {
                                // Thông báo lỗi nếu dữ liệu không phù hợp
                                throw new \Exception("Dòng số " . ($key + 1) . ": '{$row[$rowNumber]}' không có trong hệ thống.");
                            }
                        }
                       
                        if ($column == 'mã số nhân viên') {
                           
                            $msnv = $row[$rowNumber];
                        }
                        if ($column == 'người sử dụng' && $msnv !== "") {   
                                $userName = User::where('username', $msnv)->first();
                                if ($userName) {
                                    $assetTransactionData->user_id = $userName->id;
                                } else {
                                    throw new \Exception("Dòng số " . ($key + 1) . ": '{$row[$rowNumber]}' với mã số nhân viên '{$msnv}' chưa có trong hệ thống ");
                                }
                        
                        }
                        if ($column == 'ghi chú') {
                            if ($row[$rowNumber] == 'Bàn giao đầu kỳ') {
                                $assetTransactionData->note = $row[$rowNumber];
                                $assetTransactionData->confirm = 1;
                            } else {
                                // Thông báo lỗi nếu dữ liệu không phù hợp
                                throw new \Exception("Dòng số " . ($key + 1) . ": Nội dung ghi chú không đúng định dạng.");
                            }
                        }
                        if ($column == 'phòng ban' ) {
                            $fakeDepartment =  $row[$rowNumber];
                            $department = Department::where('name', $row[$rowNumber])->first();
                            if ($department) {
                                $assetTransactionData->department_id = $department->id;
                            } 
                        }
                        if ($column == 'mã công ty') {
                            // Lấy mã công ty
                            $companyCode = $row[$rowNumber];
                            
                            // Kiểm tra nếu mã công ty không nằm trong phòng ban thì báo lỗi
                            if ($department && $department->company_id !== $companyCode) {
                                throw new \Exception("Dòng số " . ($key + 1) . ": Mã công ty '{$companyCode}' không nằm trong phòng ban '{$fakeDepartment}' đã nhập.");
                            }
                        }
                    } else {
                        throw new \Exception("Vui lòng không được tự ý thêm cột");
                    }
                }
                if($assetTransactionData->user_id == null && $assetTransactionData->department_id == null){
                    throw new \Exception("Dòng số " . ($key + 1) . ": Vui lòng nhập (Mã số nhân viên - Người sử dụng) hoặc (Phòng ban - Công ty)..");
                }
                if($assetTransactionData->user_id && $assetTransactionData->department_id){
                    throw new \Exception("Dòng số " . ($key + 1) . ": Chỉ được nhập (Mã số nhân viên - Người sử dụng) hoặc (Phòng ban - Công ty)..");
                } else {
                    $assetTransactionData->save();
                }
                $assetTransactionItem->asset_transaction_id = $assetTransactionData->id;
                $assetTransactionItem->user_id = $assetTransactionData->user_id;
                $assetTransactionItem->department_id = $assetTransactionData->department_id;
                $assetTransactionItem->created_at = null;
                $assetTransactionItem->updated_at = null;
                $assetTransactionItem->save();

                // cập nhật người sử dụng tài sản
                $updateAssetUserId = Asset::where('id', $assetTransactionItem->objectable_id)->update(['user_id' => $assetTransactionData->user_id, 'department_id' => $assetTransactionData->department_id]);

                // Lưu vào AssetUse để tiến hành thu hồi

                $assetUse = AssetUse::create([
                    'user_id' => $assetTransactionItem->user_id,
                    'objectable_id' => $assetTransactionItem->objectable_id,
                    'objectable_type' => $assetTransactionItem->objectable_type,
                    'quantity' => $assetTransactionItem->quantity,
                    'asset_warehouse_id' => $assetTransactionItem->asset_warehouse_id,
                    'create_by' => $assetTransactionData->create_by,
                    'date_transaction' => null,
                    'department_id' => $assetTransactionData->department_id,
                    'created_at' => null,
                    'updated_at' => null,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        }
    }
}
