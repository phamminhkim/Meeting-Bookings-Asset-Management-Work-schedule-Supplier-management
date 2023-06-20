<?php

use Illuminate\Database\Seeder;
use App\Models\shared\Department;
use App\Models\shared\Company;
use Illuminate\Support\Facades\DB;
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $department = Department::where('id','23')->get();
        // if(!$department || $department->count()==0){
        //     Department::create(['id'=>'23', 'company_id' => '2000','code'=>'File', 'name' => 'PX File']);
        // }
        
        // DB::table('departments')->delete();
        $departments = config('company_struct.departments');

        foreach ($departments as $company_name => $deparment_value) {
            foreach ($deparment_value as $deparment_code => $deparment_name) {
                $department = Department::where('code',$deparment_code)->get();
                if(!$department || $department->count()==0){
                    $company = Company::select('id')->where('name', $company_name)->first();
                    if ($company) {
                        Department::create(['company_id' => $company->id,'code' => $deparment_code, 'name' => $deparment_name]);
                    }
                }
            }
        }
    }
}
