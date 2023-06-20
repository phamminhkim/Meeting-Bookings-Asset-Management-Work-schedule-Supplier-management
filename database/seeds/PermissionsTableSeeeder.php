<?php

use App\Models\shared\Permission;
use App\Models\shared\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //DB::table('permissions')->delete();
        // Permission::create(['name' => 'in_phieu_giao_hang', 'description' => 'In phiếu giao hàng']);
        // Permission::create(['name' => 'upload_don_hang', 'description' => 'Upload đơn hàng']);
        // Permission::create(['name' => 'upload_mvc', 'description' => 'Upload mã vận chuyển']);


        $list = Role::where('name','like','all')->get();
        //  dd($list );

          if(!$list || $list->count()==0){
            $allRole = Role::create(['name' => 'all', 'description' => 'All', 'active' => '1']);
          }

        $list = Permission::where('name','like','%_team')->get();
      //  dd($list );
        if(!$list || $list->count()==0){
            Permission::create(['name' => 'create_team', 'description' => 'Tạo Team']);
            Permission::create(['name' => 'review_team', 'description' => 'Xem Team']);
            Permission::create(['name' => 'update_team', 'description' => 'Sửa Team']);
            Permission::create(['name' => 'delete_team', 'description' => 'Xoá Team']);
            Permission::create(['name' => 'restore_team', 'description' => 'Restore Team']);
        }
        $list = Permission::where('name','like','%_department')->get();
      //  dd($list );
        if(!$list || $list->count()==0){
            Permission::create(['name' => 'create_department', 'description' => 'Tạo phòng ban']);
            Permission::create(['name' => 'update_department', 'description' => 'Sửa phòng ban']);
            Permission::create(['name' => 'delete_department', 'description' => 'Xoá phòng ban']);
            Permission::create(['name' => 'restore_department', 'description' => 'Restore phòng ban']);
        }
        $list = Permission::where('name','like','%_tickettype')->get();
        //  dd($list );
          if(!$list || $list->count()==0){
              Permission::create(['name' => 'create_tickettype', 'description' => 'Tạo ticket type']);
              Permission::create(['name' => 'review_tickettype', 'description' => 'Xem ticket type']);
              Permission::create(['name' => 'update_tickettype', 'description' => 'Sửa ticket type']);
              Permission::create(['name' => 'delete_tickettype', 'description' => 'Xoá ticket type']);
              Permission::create(['name' => 'restore_tickettype', 'description' => 'Restore ticket type']);
          }
          $list = Permission::where('name','like','%_tickettype')->get();
          //  dd($list );
            if(!$list || $list->count()==0){
                Permission::create(['name' => 'create_tickettype', 'description' => 'Tạo ticket type']);
                Permission::create(['name' => 'review_tickettype', 'description' => 'Xem ticket type']);
                Permission::create(['name' => 'update_tickettype', 'description' => 'Sửa ticket type']);
                Permission::create(['name' => 'delete_tickettype', 'description' => 'Xoá ticket type']);
                Permission::create(['name' => 'restore_tickettype', 'description' => 'Restore ticket type']);
            }
            $list = Permission::where('name','like','%_approvestep')->get();
            //  dd($list );
              if(!$list || $list->count()==0){
                  Permission::create(['name' => 'create_approvestep', 'description' => 'Tạo bước duyệt']);
                  Permission::create(['name' => 'review_approvestep', 'description' => 'Xem bước duyệt']);
                  Permission::create(['name' => 'update_approvestep', 'description' => 'Sửa ticket type']);
                  Permission::create(['name' => 'delete_approvestep', 'description' => 'Xoá ticket type']);
                  Permission::create(['name' => 'restore_approvestep', 'description' => 'Restore ticket type']);
              }
              $list = Permission::where('name','like','%_documenttype')->get();
              //  dd($list );
                if(!$list || $list->count()==0){
                    Permission::create(['name' => 'create_documenttype', 'description' => 'Tạo loại chứng từ']);
                    Permission::create(['name' => 'review_documenttype', 'description' => 'Xem loại chứng từ']);
                    Permission::create(['name' => 'update_documenttype', 'description' => 'Sửa loại chứng từ']);
                    Permission::create(['name' => 'delete_documenttype', 'description' => 'Xoá loại chứng từ']);
                    Permission::create(['name' => 'restore_documenttype', 'description' => 'Restore loại chứng từ']);
                }
                $list = Permission::where('name','like','%_documenttype')->get();
                //  dd($list );
                  if(!$list || $list->count()==0){
                      Permission::create(['name' => 'create_group', 'description' => 'Tạo group']);
                      Permission::create(['name' => 'review_group', 'description' => 'Xem group']);
                      Permission::create(['name' => 'update_group', 'description' => 'Sửa group']);
                      Permission::create(['name' => 'delete_group', 'description' => 'Xoá group']);
                      Permission::create(['name' => 'restore_group', 'description' => 'Restore group']);
                  }
                $list = Permission::where('name','like','%_approvelimit')->get();
                //  dd($list );
                  if(!$list || $list->count()==0){
                      Permission::create(['name' => 'create_approvelimit', 'description' => 'Xem hạn mức']);
                      Permission::create(['name' => 'review_approvelimit', 'description' => 'Xem hạn mức']);
                      Permission::create(['name' => 'update_approvelimit', 'description' => 'Sửa hạn mức']);
                      Permission::create(['name' => 'delete_approvelimit', 'description' => 'Xoá hạn mức']);
                      Permission::create(['name' => 'restore_approvelimit', 'description' => 'Restore hạn mức']);
                  }
                $list = Permission::where('name','like','%_approverouting')->get();
                //  dd($list );
                  if(!$list || $list->count()==0){
                      Permission::create(['name' => 'create_approverouting', 'description' => 'Tạo hạn mức']);
                      Permission::create(['name' => 'review_approverouting', 'description' => 'Xem hạn mức']);
                      Permission::create(['name' => 'update_approverouting', 'description' => 'Sửa hạn mức']);
                      Permission::create(['name' => 'delete_approverouting', 'description' => 'Xoá hạn mức']);
                      Permission::create(['name' => 'restore_approverouting', 'description' => 'Restore hạn mức']);
                  }
                $list = Permission::where('name','like','%_contracts')->get();
                //  dd($list );
                  if(!$list || $list->count()==0){
                      Permission::create(['name' => 'create_contracts', 'description' => 'Tạo hợp đồng']);
                      Permission::create(['name' => 'review_contracts', 'description' => 'Xem hợp đồng']);
                      Permission::create(['name' => 'update_contracts', 'description' => 'Sửa hợp đồng']);
                      Permission::create(['name' => 'delete_contracts', 'description' => 'Xoá hợp đồng']);
                      Permission::create(['name' => 'restore_contracts', 'description' => 'Restore hợp đồng']);
                  }
                  $list = Permission::where('name','like','%_yctt')->get();
                  //  dd($list );
                    if(!$list || $list->count()==0){
                        Permission::create(['name' => 'create_yctt', 'description' => 'Tạo yêu cầu thanh toán']);
                        Permission::create(['name' => 'review_yctt', 'description' => 'Xem yêu cầu thanh toán']);
                        Permission::create(['name' => 'update_yctt', 'description' => 'Sửa yêu cầu thanh toán']);
                        Permission::create(['name' => 'delete_yctt', 'description' => 'Xoá yêu cầu thanh toán']);
                        Permission::create(['name' => 'restore_yctt', 'description' => 'Restore yêu cầu thanh toán']);
                        Permission::create(['name' => 'review_all_yctt', 'description' => 'Xem yêu cầu thanh toán tất cả các công ty']);

                    }
                    $list = Permission::where('name','like','update_dathanhtoan_yctt')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'update_dathanhtoan_yctt', 'description' => 'Hoàn tất yêu cầu thanh toán']);
                    }
                    $list = Permission::where('name','like','review_company_yctt')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'review_company_yctt', 'description' => 'Xem yêu cầu thanh toán trong công ty']);
                    }
                    $list = Permission::where('name','like','update_miss_invoice_yctt')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'update_miss_invoice_yctt', 'description' => 'Thiết lập nợ hoá đơn - yêu cầu thanh toán']);
                    }
                    $list = Permission::where('name','like','check_attach_yctt')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'check_attach_yctt', 'description' => 'Check đã kiểm tra file - yêu cầu thanh toán']);
                    }
                    $list = Permission::where('name','like','update_hard_doc_yctt')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'update_hard_doc_yctt', 'description' => 'Ngày nhận bản cứng chứng từ yêu cầu thanh toán']);
                    }
                    //1/11/2021:bổ sung quyền hủy chứng từ
                    $list = Permission::where('name','like','update_cancel_yctt')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'update_cancel_yctt', 'description' => 'Hủy chứng từ yêu cầu thanh toán']);
                    }
                    //1/11/2021 hungnn:bổ sung quyền check đã in chứng từ
                    $list = Permission::where('name','like','printed_check_yctt')->get();
                    if(!$list || $list->count()==0){
                        Permission::create(['name' => 'printed_check_yctt', 'description' => 'Check đã in - yêu cầu thanh toán']);
                    }

                    //Document
                    $list = Permission::where('name','like','review_all_document')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'review_all_document', 'description' => 'Xem tờ trình tất cả các công ty']);
                    }
                    $list = Permission::where('name','like','review_company_document')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'review_company_document', 'description' => 'Xem tờ trình trong công ty']);
                    }
                    $list = Permission::where('name','like','review_document')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'review_document', 'description' => 'Xem tờ trình']);
                    }

                    $list = Permission::where('name','like','create_document')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'create_document', 'description' => 'Tạo tờ trình']);
                    }
                    $list = Permission::where('name','like','update_document')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'update_document', 'description' => 'Sửa tờ trình']);
                    }
                     //Duyệt giá
                     $list = Permission::where('name','like','review_all_ycdg')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'review_all_ycdg', 'description' => 'Xem duyệt giá tất cả các công ty']);
                     }
                     $list = Permission::where('name','like','review_company_ycdg')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'review_company_ycdg', 'description' => 'Xem duyệt giá trong công ty']);
                     }
                     $list = Permission::where('name','like','review_ycdg')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'review_ycdg', 'description' => 'Xem duyệt giá']);
                     }

                     $list = Permission::where('name','like','create_ycdg')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'create_ycdg', 'description' => 'Tạo duyệt giá']);
                     }
                     $list = Permission::where('name','like','update_ycdg')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'update_ycdg', 'description' => 'Sửa duyệt giá']);
                     }
                     $list = Permission::where('name','like','update_cancel_ycdg')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'update_cancel_ycdg', 'description' => 'Hủy duyệt giá']);
                     }
                    //
                    //issue
                    $list = Permission::where('name','like','review_all_issue')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'review_all_issue', 'description' => 'Xem tờ yêu cầu dịch vụ tất cả các công ty']);
                    }
                    $list = Permission::where('name','like','review_company_issue')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'review_company_issue', 'description' => 'Xem tờ yêu cầu dịch vụ trong công ty']);
                    }
                    $list = Permission::where('name','like','review_issue')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'review_issue', 'description' => 'Xem tờ yêu cầu dịch vụ']);
                    }

                    $list = Permission::where('name','like','create_issue')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'create_issue', 'description' => 'Tạo tờ yêu cầu dịch vụ']);
                    }
                    $list = Permission::where('name','like','update_issue')->get();
                    if(!$list || $list->count()==0){
                      Permission::create(['name' => 'update_issue', 'description' => 'Sửa tờ yêu cầu dịch vụ']);
                    }



					 //Phiếu car
					 $list = Permission::where('name','like','review_all_car')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'review_all_car', 'description' => 'Xem tất cả phiếu car']);
                     }
					 $list = Permission::where('name','like','review_company_car')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'review_company_car', 'description' => 'Xem phiếu car theo công ty']);
                     }
					 $list = Permission::where('name','like','review_department_car')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'review_department_car', 'description' => 'Xem phiếu car theo phòng ban']);
                     }
					 $list = Permission::where('name','like','review_car')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'review_car', 'description' => 'Xem phiếu car']);
                     }
					 $list = Permission::where('name','like','create_car')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'create_car', 'description' => 'Tạo phiếu car']);
                     }
					 $list = Permission::where('name','like','review_statistical_car')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'review_statistical_car', 'description' => 'Xem thống kê phiếu car']);
                     }
           $list = Permission::where('name','like','update_car')->get();
                     if(!$list || $list->count()==0){
                       Permission::create(['name' => 'update_car', 'description' => 'Cập nhật lại phiếu car']);
                     }
    }
}
