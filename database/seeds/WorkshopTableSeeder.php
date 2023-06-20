<?php

use App\Models\shared\Workshop;
use App\Models\shared\Department;
use App\Models\shared\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkshopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('workshops')->delete();
        $workshops = config('company_struct.workshops');

        foreach ($workshops as $company_name => $deparment_value) {
            foreach ($deparment_value as $deparment_code => $workshop_value) {
                foreach ($workshop_value as $workshop_code => $workshop_name) {
                    $workshop = Workshop::where('code',$workshop_code)->get();
                    if(!$workshop || $workshop->count()==0){
                        $company = Company::select('id')->where('name', $company_name)->first();
                        if ($company) {
                            $department = Department::select('id')->where('code', $deparment_code)->first();
                            if ($department) {
                                Workshop::create(['company_id' => $company->id, 'department_id' => $department->id, 'code' => $workshop_code, 'name' => $workshop_name]);
                            }
                        }
                        
                    }
                }
            }
        }
    }
}
