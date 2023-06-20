<?php

namespace App\Repositories\approve;

use App\Models\shared\ApprovedLimit;
use App\Models\shared\ApprovedRouting;
use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\Team;
use App\User;

final class ApproveRoutingHelper
{

    public static function get_team($company_id, $document_type_id, $group_id, $budget_type, $amount, $amount_exchanged, $currency, $payment_type_id, $amount_out_budget, $amount_out_exchanged)
    {
        // $total_amount = $amount / 1000000;
        if ($amount == null) $amount = 0;
        $total_amount = $amount;
        if ($amount_exchanged == null) $amount_exchanged = 0;
        $total_amount_out_budget = $amount_out_budget;

        //dd('total_amount:'.$total_amount. "- amount_out_budget:".$total_amount_out_budget);
        $documentType = DocumentType::find($document_type_id);
        //Duyệt vượt công ty
        $group = Group::find($group_id);
        $company_id = "";
        if ($group) {
            $company_id = $group->company_id;
        }
        if ($documentType && $documentType->currency_approved  && $documentType->currency_approved != '') {
            $currency = $documentType->currency_approved;
            $total_amount = $amount_exchanged;

            $total_amount_out_budget = $amount_out_exchanged;
            // dd(   $amount_exchanged);
        }

        $team_id = "";

        $limit = ApprovedLimit::where('company_id', $company_id)
            ->where('document_type_id', $document_type_id)
            ->where('payment_type_id', $payment_type_id)
            ->where('currency', $currency)
            ->where('budget_type', $budget_type)
            ->where([
                ['from', '<=', $total_amount],
                ['to', '>=', $total_amount],
                ['from_sub', '<=', $total_amount_out_budget],
                ['to_sub', '>=', $total_amount_out_budget],
            ])
            ->first();

        if ($limit) {
            $routing = ApprovedRouting::where('document_type_id', $document_type_id)
                ->where('group_id', $group_id)
                ->where('payment_type_id', $payment_type_id)
                ->where('approved_limit_code', $limit->code)->first();

            if ($routing) {
                $team_id = $routing->team_id;
            }
        }
        // dd($routing);
        return $team_id;
    }
    public static function createTeamFrom($team_id)
    {
        $teamOld = Team::find($team_id);

        $team = new Team();
        $team->name =  '_AUTO';
        $team->module =  "";
        $team->active = '1';
        $team->description = $teamOld->description.'_'.$teamOld->id.'_CLONE';
        $team->save();
        
        foreach ($teamOld->users as $u) {

            $user = User::find($u->pivot->user_id);

            $level =  $u->pivot->level;
            $step = $u->pivot->step;
            $review = $u->pivot->review;
            $sign =  $u->pivot->sign;

            $team->users()->attach($user, ['level' => $level, 'step' => $step, 'review' => $review, 'sign' => $sign]);
        }
        //  dd(" team_id= ".$team->id. ",level=".$level .", step=". $step .", review=".$review .", sign=".$sign);

        return $team->id;
    }
    public static function mergeTwoTeam($confirmTeamID, $considerTeamID)
    {
        $team = new Team();
        $team->name =  '_AUTO';
        $team->description = '_AUTO';
        $team->module =  "";
        $team->active = '1';
        $team->save();

        // Thêm team xem xét
        $confirmTeam = Team::with('users')->get()->find($confirmTeamID);
        foreach ($confirmTeam->users as $u) {
            $user = User::find($u->pivot->user_id);

            $level =  $u->pivot->level;
            $step = 1;
            $review = $u->pivot->review;
            $sign =  $u->pivot->sign;

            $team->users()->attach($user, ['level' => $level, 'step' => $step, 'review' => $review, 'sign' => $sign]);
        }

        $considerTeam = Team::with('users')->get()->find($considerTeamID);
        foreach ($considerTeam->users as $u) {
            $user = User::find($u->pivot->user_id);

            $level =  $u->pivot->level;
            $step = 2;
            $review = $u->pivot->review;
            $sign =  $u->pivot->sign;

            $team->users()->attach($user, ['level' => $level, 'step' => $step, 'review' => $review, 'sign' => $sign]);
        }

        return $team->id;
    }
}
