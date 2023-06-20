<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function role(Request $request)
    {
        return view('category.role');
    }
    public function permission(Request $request)
    {
        return view('category.permission');
    }
    public function user(Request $request)
    {
        return view('category.user');
    }
    public function department(Request $request)
    {
        return view('category.hr.department');
    }
    public function company(Request $request)
    {
        return view('category.hr.company');
    }
    public function workshop(Request $request)
    {
        return view('category.hr.workshop');
    }
    public function party(Request $request)
    {
        return view('category.hr.party');
    }
    public function employee(Request $request)
    {
        return view('category.hr.employee');
    }
    public function resigndata(Request $request)
    {
        return view('category.hr.resigndata');
    }
    public function directtype(Request $request)
    {
        return view('category.hr.directtype');
    }
    public function employeetype(Request $request)
    {
        return view('category.hr.employeetype');
    }
    public function employmenttype(Request $request)
    {
        return view('category.hr.employmenttype');
    }
    public function jobtitle(Request $request)
    {
        return view('category.hr.jobtitle');
    }
    public function resigntype(Request $request)
    {
        return view('category.hr.resigntype');
    }
    public function team(Request $request)
    {

        $type = $request->type;
        $team_id = $request->id;
        return view('category.team',compact('type','team_id'));
    }
    public function position(Request $request)
    {
        
        $type = $request->type;
        $postion_id = $request->id;
        return view('category.position',compact('type','postion_id'));

    }
    public function positionapp(Request $request)
    {
        
        return view('category.position_app');
    }
    public function carerror(Request $request)
    {
        $department = $request->department_id;
        $active = $request->active;
        return view('category.car_error',compact('department','active'));
    }
    public function typecar(Request $request)
    {
        return view('category.type_car');
    }
    public function standard(Request $request)
    {
        return view('category.standard');
    }
    public function serviceCategory(Request $request)
    {
        return view('category.service_category');
    }
    public function payrequestType(Request $request)
    {
        return view('category.payrequest_type');
    }

    public function approveStep(Request $request)
    {
        return view('category.approve_step');
    }
    public function docmentType(Request $request)
    {
        return view('category.document_type');
    }
    public function documentGroup(Request $request)
    {
        return view('category.document_group');
    }
    public function docbrowserType(Request $request)
    {
        return view('category.docbrowser_type');
    }
    public function group(Request $request)
    {
        $type = $request->type;
        $group_id = $request->id;
        return view('category.group',compact('type','group_id'));
    }
    public function approveLimit(Request $request)
    {
        return view('category.approve_limit' );
    }
    public function approveRouting(Request $request)
    {
        return view('category.approve_routing' );
    }
    public function vendor(Request $request)
    {
        return view('category.vendor' );
    }
    public function product(Request $request)
    {

        return view('category.product' );
    }
    public function workflow(Request $request)
    {
        $type = $request->type;
        $wfmain_id = $request->id;
        return view('category.workflow',compact('type','wfmain_id'));
    }
    public function workflowstep(Request $request)
    {
        return view('category.workflowstep' );
    }
    public function wftusertype(Request $request)
    {
        return view('category.wftusertype' );
    }
    public function wfapptype(Request $request)
    {
        return view('category.wfapptype' );
    }
    public function wfstepdetail(Request $request)
    {
        return view('category.wfstepdetail' );
    }
    public function hrconfigtype(Request $request)
    {
        return view('category.hrconfigtype' );
    }
    public function hrconfigprice(Request $request)
    {
        return view('category.hrconfigprice' );
    }
    public function module(Request $request)
    {
        return view('category.module' );
    }
    public function workflowtype(Request $request)
    {
        return view('category.wlworkflowtype' );
    }
    public function workflowjobtype(Request $request)
    {
        return view('category.wlworkflowjobtype' );
    }
    public function workflowvariable(Request $request)
    {
        return view('category.wlworkflow_variable' );
    }
    public function workflowcondition(Request $request)
    {
        return view('category.wlworkflow_condition' );
    }
    public function uniqueid(Request $request)
    {
        return view('category.uniqueid' );
    }

    public function goodtypes(Request $request){
        $type = $request->type;
        $id =   $request->id;

        
        return view('sloc.goodtypes',compact('type','id'));
    }
    public function goods(Request $request){
        $type = $request->type;
        $id =   $request->id;

        
        return view('sloc.goods',compact('type','id'));
    }
    public function goodunits(Request $request){
        return view('sloc.goodunits');
    }
    public function goodslist(Request $request){
        return view('sloc.goodslist');
    }
    public function calendartype(Request $request){
        return view('calendar.calendartype');
    }
    public function calendarholiday(Request $request){
        return view('calendar.calendarholiday');
    }
    public function assets(Request $request){
        return view('asset.assets');
    }
    public function assettype(Request $request){
        return view('asset.assettype');
    }
    public function assetstock(Request $request){
        return view('asset.assetstock');
    }
    public function assetchange(Request $request){
        $type = $request->type;
        $id =   $request->id;
        return view('asset.assetchange',compact('type','id'));
    }
    public function assetsetting(Request $request){
        return view('asset.assetsetting');
    }
    public function assetgroup(Request $request){
        return view('asset.assetgroup');
    }
    public function assetinventario(Request $request){
        $type = $request->type;
        $id =   $request->id;
        $notification_id = $request->notification_id;
        $time = $request->time;
        $user_id = $request->user_id;
        $inven = $request->inven;
        $ware_house = $request->ware_house;
        return view('asset.assetinventario',compact('type','id','notification_id','user_id','inven','ware_house','time'));
    }
    public function assetmembers(Request $request){
        return view('asset.assetmembers');
    }
    public function assetreport(Request $request){
        return view('asset.assetreport');
    }
    public function assetstatus(Request $request){
        return view('asset.assetstatus');
    }
    public function assetmy(Request $request){
        $type = $request->type;
        $id =   $request->id;
        return view('asset.assetmy',compact('type','id'));
    }
    public function assetrecord(Request $request){
        $type = $request->type;
        $id =   $request->id;
        return view('asset.asset_report',compact('type','id'));
    }

    public function pageQuestion(Request $request){
        $type = $request->type;
        $tag_id = $request->tag_id;
        $question_id =   $request->question_id;
        return view('qa.question',compact('type','question_id','tag_id'));
    }
    public function demo(Request $request){
        return view('demo.index');
    }
    
}
