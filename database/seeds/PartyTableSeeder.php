<?php

use App\Models\shared\Party;
use App\Models\shared\Workshop;
use App\Models\shared\Department;
use App\Models\shared\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('parties')->delete();
        $parties = config('company_struct.parties');
        // Get company name
        foreach ($parties as $company_name => $deparment_value) {
            // Get department code
            foreach ($deparment_value as $deparment_code => $workshop_value) {
                // Get workshop code
                foreach ($workshop_value as $workshop_code => $party_value) {
                    // Get party code
                    foreach ($party_value as $party_code => $party_name) {
                        // Add party
                        $party = Party::where('code',$party_code)->get();
                        if(!$party || $party->count()==0){
                            $company = Company::select('id')->where('name', $company_name)->first();
                            if ($company) {
                                $department = Department::select('id')->where('code', $deparment_code)->first();
                                if ($department) {
                                    $workshop = Workshop::select('id')->where('code', $workshop_code)->first();
                                    if ($workshop) {
                                        Party::create(['company_id' => $company->id, 'department_id' => $department->id, 'workshop_id' => $workshop->id, 'code' => $party_code, 'name' => $party_name]);
                                    }
                                }
                            }
                            
                        }
                    }
                }
            }
        }
    }
}
