<?php

use App\Http\Resources\Users;
use App\Models\shared\Menu;
use App\Models\shared\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('menus')->delete();
        $menu = Menu::all()->sortByDesc('sort')->first();
        if ($menu) {
            $sort = $menu->sort;
        } else {
            $sort = 1;
        }
 
        $allRole = Role::where('name', 'all')->first();
        if (!$allRole) {
            $allRole = Role::create(['name' => 'all', 'description' => 'All', 'active' => '1']);
        }
        //Phần menu phải chạy lại từ đầu để 
        //Category -Danh mục
        $DanhMuc = Menu::where('title', 'menu.category')->first();
        if (!$DanhMuc  ) {
            $DanhMuc = Menu::create(['title' => 'menu.category', 'parent' => '0', 'sort' => $sort++, 'icon' => 'nav-icon fas fa-th', 'link' => "#", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($DanhMuc);
        }
        $list = Menu::where('title', 'menu.department')->get();
        if (!$list || $list->count() == 0) {
            $menuPhongBan = Menu::create(['title' => 'menu.department', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/department", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($menuPhongBan);
        }
        $list = Menu::where('title', 'menu.team')->get();
        if (!$list || $list->count() == 0) {
            $menuTeam = Menu::create(['title' => 'menu.team', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/team", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($menuTeam);
        }      
        $list = Menu::where('title', 'menu.ticket_type')->get();
        if (!$list || $list->count() == 0) {
            $menuTeam = Menu::create(['title' => 'menu.team', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/team", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($menuTeam);
        }   
        $list = Menu::where('title', 'menu.ticket_type')->get();
        if (!$list || $list->count() == 0) {
            $menuServiceCategory = Menu::create(['title' => 'menu.ticket_type', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/servicecategory", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($menuServiceCategory);
        }               
        $list = Menu::where('title', 'menu.config_step')->get();
        if (!$list || $list->count() == 0) {
            $approveStep = Menu::create(['title' => 'menu.config_step', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/approvestep", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($approveStep);
        }        
      
        $list = Menu::where('title', 'menu.document_type')->get();
        if (!$list || $list->count() == 0) {
            $documentType = Menu::create(['title' => 'menu.document_type', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/documenttype", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($documentType);
        }      
        $list = Menu::where('title', 'menu.group')->get();
        if (!$list || $list->count() == 0) {
            $group = Menu::create(['title' => 'menu.group', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/group", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($group);
        }         
        $list = Menu::where('title', 'menu.limit')->get();
        if (!$list || $list->count() == 0) {
            $approveLimit = Menu::create(['title' => 'menu.limit', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/approvelimit", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($approveLimit);
        }         
        $list = Menu::where('title', 'menu.config_routing')->get();
        if (!$list || $list->count() == 0) {
            $approveRouting = Menu::create(['title' => 'menu.config_routing', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/approverouting", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($approveRouting);
        }    
        $list = Menu::where('title', 'menu.vendor')->get();
        if (!$list || $list->count() == 0) {
            $vendor = Menu::create(['title' => 'menu.vendor', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/vendor", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($vendor);
        }        
        //Ticket
        $TicketHeader = Menu::where('title', 'menu.ticket')->first();
        if (!$TicketHeader  ) {
            $TicketHeader = Menu::create(['title' => 'menu.ticket', 'parent' => '0', 'sort' => $sort++, 'icon' => 'fas fa-ticket-alt', 'link' => "#", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($TicketHeader);
        }        
        $TicketHeader = Menu::where('title', 'menu.ticket')->first();
        if (!$TicketHeader  ) {
            $TicketHeader = Menu::create(['title' => 'menu.ticket', 'parent' => '0', 'sort' => $sort++, 'icon' => 'fas fa-ticket-alt', 'link' => "#", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($TicketHeader);
        }    
        $list = Menu::where('title', 'menu.create_ticket')->get();
        if (!$list || $list->count() == 0) {
            $ticketCreate = Menu::create(['title' => 'menu.create_ticket', 'parent' => $TicketHeader->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "service/create", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($ticketCreate);
        }     
        $list = Menu::where('title', 'menu.list_ticket')->get();
        if (!$list || $list->count() == 0) {
            $ticketList = Menu::create(['title' => 'menu.list_ticket', 'parent' => $TicketHeader->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "service/index", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($ticketList);
        }         

        //Payment
        $PaymentHeader = Menu::where('title', 'menu.payment')->first();
        if (!$PaymentHeader  ) {
            $PaymentHeader = Menu::create(['title' => 'menu.payment', 'parent' => '0', 'sort' => $sort++, 'icon' => 'fas fa-money-check-alt', 'link' => "#", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($PaymentHeader);
        }   
        $list = Menu::where('title', 'menu.contract')->get();
        if (!$list || $list->count() == 0) {
            $contract = Menu::create(['title' => 'menu.contract', 'parent' => $PaymentHeader->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "payment/contracts", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($contract);
        }          
        $list = Menu::where('title', 'menu.payment_request')->get();
        if (!$list || $list->count() == 0) {
            $payrequest = Menu::create(['title' => 'menu.payment_request', 'parent' => $PaymentHeader->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "payment/requests", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($payrequest);
        }         
        $list = Menu::where('title', 'menu.approved_payment_request')->get();
        if (!$list || $list->count() == 0) {
            $approve_request = Menu::create(['title' => 'menu.approved_payment_request', 'parent' => $PaymentHeader->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "approve/request", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($approve_request);
        }   

        $list = Menu::where('title', 'menu.documents')->get();
        if (!$list || $list->count() == 0) {
            $approve_request = Menu::create(['title' => 'menu.documents', 'parent' => $PaymentHeader->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "documents", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($approve_request);
        }   
        $list = Menu::where('title', 'approved_document')->get();
        if (!$list || $list->count() == 0) {
            $approve_request = Menu::create(['title' => 'approved_document', 'parent' => $PaymentHeader->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "approve/document", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($approve_request);
        }
        $list = Menu::where('title', 'menu.statistics')->get();
        if (!$list || $list->count() == 0) {
            $payrequest = Menu::create(['title' => 'menu.statistics', 'parent' => $PaymentHeader->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "payment/statistics", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($payrequest);
        } 		
        //Phieu car
		$list = Menu::where('title', 'menu.workflow')->get();
        if (!$list || $list->count() == 0) {
            $workflow = Menu::create(['title' => 'menu.workflow', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/workflow", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($workflow);
        }  
		$list = Menu::where('title', 'menu.workflowstep')->get();
        if (!$list || $list->count() == 0) {
            $workflowstep = Menu::create(['title' => 'menu.workflowstep', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/workflowstep", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($workflowstep);
        }
		$list = Menu::where('title', 'menu.wftusertype')->get();
        if (!$list || $list->count() == 0) {
            $wftusertype = Menu::create(['title' => 'menu.wftusertype', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/wftusertype", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($wftusertype);
        } 
		$list = Menu::where('title', 'menu.wfapptype')->get();
        if (!$list || $list->count() == 0) {
            $wfapptype = Menu::create(['title' => 'menu.wfapptype', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/wfapptype", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($wfapptype);
        }
		$list = Menu::where('title', 'menu.wfstepdetail')->get();
        if (!$list || $list->count() == 0) {
            $wfstepdetail = Menu::create(['title' => 'menu.wfstepdetail', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/wfstepdetail", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($wfstepdetail);
        }
		$list = Menu::where('title', 'menu.position')->get();
        if (!$list || $list->count() == 0) {
            $position = Menu::create(['title' => 'menu.position', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/position", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($position);
        }
		$list = Menu::where('title', 'menu.positionapp')->get();
        if (!$list || $list->count() == 0) {
            $positionapp = Menu::create(['title' => 'menu.positionapp', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/positionapp", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($positionapp);
        }
		$list = Menu::where('title', 'menu.carerror')->get();
        if (!$list || $list->count() == 0) {
            $carerror = Menu::create(['title' => 'menu.carerror', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/carerror", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($carerror);
        }
		$list = Menu::where('title', 'menu.typecar')->get();
        if (!$list || $list->count() == 0) {
            $carerror = Menu::create(['title' => 'menu.typecar', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/typecar", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($carerror);
        }
		$list = Menu::where('title', 'menu.standard')->get();
        if (!$list || $list->count() == 0) {
            $carerror = Menu::create(['title' => 'menu.standard', 'parent' => $DanhMuc->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "category/standard", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($carerror);
        }
		$list = Menu::where('title', 'menu.tqm')->get();
        if (!$list || $list->count() == 0) {
            $tqm = Menu::create(['title' => 'menu.tqm', 'parent' => '0', 'sort' => $sort++, 'icon' => 'fas fa-text-width', 'link' => "#", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($tqm);
        }
		$list = Menu::where('title', 'menu.car')->get();
        if (!$list || $list->count() == 0) {
            $car = Menu::create(['title' => 'menu.car', 'parent' => $tqm->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "car/systems", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($car);
        }
		$tqm = Menu::where('title', 'menu.tqm')->first();
		$list = Menu::where('title', 'menu.statistical')->get();
        if (!$list || $list->count() == 0) {
            $car = Menu::create(['title' => 'menu.statistical', 'parent' => $tqm->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "car/statistical", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($car);
        }
		$list = Menu::where('title', 'menu.carlog')->get();
        if (!$list || $list->count() == 0) {
            $carlog = Menu::create(['title' => 'menu.carlog', 'parent' => $tqm->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "car/carlog", 'created_at' => now(), 'updated_at' => now()]);
            $allRole->menus()->attach($carlog);
        }
        // //report_approve - Tờ trình
        // $report_approve = Menu::where('title', 'menu.report_approve')->first();
        // if (!$report_approve  ) {
        //     $report_approve = Menu::create(['title' => 'menu.report_approve', 'parent' => '0', 'sort' => $sort++, 'icon' => 'fas fa-briefcase', 'link' => "#", 'created_at' => now(), 'updated_at' => now()]);
        //     $allRole->menus()->attach($report_approve);
        // }   
        // $list = Menu::where('title', 'menu.documents')->get();
        // if (!$list || $list->count() == 0) {
        //     $document = Menu::create(['title' => 'menu.documents', 'parent' => $report_approve->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "documents", 'created_at' => now(), 'updated_at' => now()]);
        //     $allRole->menus()->attach($document);
        // }    
        // $list = Menu::where('title', 'menu.approved_document')->get();
        // if (!$list || $list->count() == 0) {
        //     $document = Menu::create(['title' => 'menu.approved_document', 'parent' => $report_approve->id, 'sort' => $sort++, 'icon' => 'far fa-circle nav-icon', 'link' => "approve/document", 'created_at' => now(), 'updated_at' => now()]);
        //     $allRole->menus()->attach($document);
        // }   
       
        $admin = User::where('username', 'admin')->first();
        $leader = User::where('username', 'leader')->first();
        //$admin = User::where('username', 'user')->first();
        // $listUser = User::all();
        // foreach ($listUser  as $key => $u) {
        //     $u->roles()->detach($allRole);
        //     $u->roles()->attach($allRole);
        // }
        $admin->roles()->detach($allRole);
        $admin->roles()->attach($allRole);

        $leader->roles()->detach($allRole);
        $leader->roles()->attach($allRole);

    }
}
