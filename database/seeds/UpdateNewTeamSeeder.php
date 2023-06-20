<?php

use App\Models\document\Document;
use App\Models\managerprice\PriceReq;
use App\Models\payment\Payrequest;
use App\Models\shared\Team;
use App\Repositories\approve\ApproveRoutingHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UpdateNewTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Lấy chứng từ request
        $list = Payrequest::whereNull('status')->orWhere('status','1')->get();
        foreach ($list as  $doc) {
                $str = "Payrequest id doc = ".$doc->id .", - team_id = ". $doc->team_id;
                try {
                    $team = Team::find($doc->team_id);

                    if ($team && $team->name != '_AUTO') {
                        $doc->teamconfig_id = $doc->team_id;
                        $doc->team_id = ApproveRoutingHelper::createTeamFrom($doc->team_id);
                        $doc->save();

                        Log::info( $str);

                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    Log::info( $str);
                }

        }
        $list = Document::whereNull('status')->orWhere('status','1')->get();
        foreach ($list as  $doc) {
                $str = "Document id doc = ".$doc->id .", - team_id = ". $doc->team_id;
                try {
                    $team = Team::find($doc->team_id);

                    if ($team && $team->name != '_AUTO') {
                        $doc->teamconfig_id = $doc->team_id;
                        $doc->team_id = ApproveRoutingHelper::createTeamFrom($doc->team_id);
                        $doc->save();
                        Log::info( $str);
                    }
                } catch (\Throwable $th) {
                    Log::info( $str);
                }

        }
        $list = PriceReq::whereNull('status')->orWhere('status','1')->get();
        foreach ($list as  $doc) {
                $str = "PriceReq id doc = ".$doc->id .", - team_id = ". $doc->team_id;
                try {
                    $team = Team::find($doc->team_id);

                    if ($team && $team->name != '_AUTO') {

                        $doc->teamconfig_id = $doc->team_id;
                        $doc->team_id = ApproveRoutingHelper::createTeamFrom($doc->team_id);
                        $doc->save();
                        Log::info( $str);
                    }
                } catch (\Throwable $th) {
                    Log::info( $str);
                }

        }
    }
}
