<?php

 
use App\Models\shared\UniqueId;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
        // $users = User::all();
        // if(count($users) == 0){
        //     $this->call(SlocTableSeeder::class);
        //     $this->call(CompanyTableSeeder::class);
    
        //     $this->call(UsersTableSeeeder::class);
        //     $this->call(RolesTableSeeeder::class);
        //     $this->call(tmdt_TrangThaiDonHang::class);
           

        // } 
        $this->call(SlocTableSeeder::class);
        $this->call(CompanyTableSeeder::class);

        $this->call(UsersTableSeeeder::class);
        $this->call(RolesTableSeeeder::class);
        $this->call(tmdt_TrangThaiDonHang::class);

        $this->call(PermissionsTableSeeeder::class);
        $this->call(MenuTableSeeder::class);
         $this->call(StepsSeeder::class);
         $this->call(CurrencySeeder::class);
         $this->call(BudgetTypeSeeder::class);
         $this->call(UniqueIdsSeeder::class);
         $this->call(ModulesSeeder::class);
         
         $this->call(PaymentTypeRunSeeder::class);
         
         $this->call(SetQuyenAdminSeeder::class);
         $this->call(DocumentTypeSeeder::class);
         $this->call(StaffSeeder::class);
         $this->call(ApproveLimitSeeder::class);
         $this->call(PaymentVourcherTypeSeeder::class);
		 $this->call(CarErrorTableDataSeeder::class);
		 $this->call(WFAppTypeTableDataSeeder::class);
		 $this->call(WFUserTypeTableDataSeeder::class);
		 $this->call(TypeCarTableDataSeeder::class);
		 $this->call(StandardSeeder::class);
		 $this->call(PositionSeeder::class);
         $this->call(DepartmentSeeder::class);
         $this->call(WorkshopTableSeeder::class);
         $this->call(PartyTableSeeder::class);
		
 
    }
}
