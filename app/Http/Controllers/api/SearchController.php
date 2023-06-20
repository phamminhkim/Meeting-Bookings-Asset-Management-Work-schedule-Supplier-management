<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\payment;
use App\Models\payment\Payrequest;
use App\Models\shared\DocumentType;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        if ($request->filled('query_string')) {
            $query_string = $request->get('query_string');

            $scopes = array(
                'payrequests' => array('serial_num', 'content', 'money_receiver', 'bank_account')
            );

            //$this->searchByRegex($request)
            $final_results = $this->searchByMatch($query_string, $scopes);
            return response()->json($this->getLimitResults($final_results, 10));
        }
    }

    public function searchByMatch($query_string, $scopes)
    {
        $final_results = array();

        try {
            foreach ($scopes as $table_name => $scope) {
                $factors = join(",", $scope);
               
                $list_results = DB::table($table_name)
                    ->select(DB::raw('*, MATCH(' . $factors . ') AGAINST (\'' . $query_string  . '\') as score'))
                    ->orderBy('score', 'desc')
                    ->limit(10)->get();

                foreach ($list_results as $result) {
                    $query_result = array();
                    
                    //$query_result['factor'] = $factor;
                    $query_result['document_type_id'] = $result->document_type_id;

                    $text_display = $result->serial_num . ' - <i>' . $result->content;

                    $text_display = preg_replace("/" . preg_quote($query_string, "/") . "/i", "<b>$0</b>", $text_display);
                    $keywords = explode(' ', $query_string);
                    foreach ($keywords as $keyword) {
                        $text_display = preg_replace("/" . preg_quote($keyword, "/") . "/i", "<b>$0</b>", $text_display);
                    }

                    $text_display = $text_display . '</i>';

                    //$query_result['note'] =  $text_display;
                    $query_result['value'] =  $text_display;
                    $query_result['past'] = false;
                    //$query_result['url'] = 'payment/requests?type=view&id=' + $result->id;

                    if (!isset($final_results[$result->id])) {
                        $final_results[$result->id] = $query_result;
                    }
                }
            }
        } catch (\Throwable $th) {
            dd($th);
        }


        return $final_results;
    }

    public function searchByRegex(Request $request)
    {
        if ($request->filled('query_string')) {
            $query_string = $request->get('query_string');

            $final_results = array();

            $factors = array('serial_num', 'content', 'money_receiver');

            $keywords = explode(' ', $query_string);
            $regex_query = str_replace(' ', '|', $query_string);

            foreach ($factors as $factor) {
                //$list_results = Payrequest::where($factor, $query_string)->orWhere($factor, 'like', $query_string . '%')->orWhere($factor, 'like', '%' . $query_string)->orWhere($factor, 'like', '%' . $query_string . '%')->take(10)->get();

                $list_results = Payrequest::where($factor, 'regexp', $regex_query)->take(10)->with('document_type')->get();

                foreach ($list_results as $result) {
                    $query_result = array();

                    $query_result['factor'] = $factor;


                    $text_display = '[' . $result->document_type->code . '] ' . $result->serial_num . ' - <i>' . $result->content;;

                    $text_display = preg_replace("/" . preg_quote($query_string, "/") . "/i", "<b>$0</b>", $text_display);
                    foreach ($keywords as $keyword) {
                        $text_display = preg_replace("/" . preg_quote($keyword, "/") . "/i", "<b>$0</b>", $text_display);
                    }

                    $text_display = $text_display . '</i>';

                    $query_result['note'] =  $text_display;
                    $query_result['value'] =  $text_display;
                    $query_result['past'] = false;
                    //$query_result['url'] = 'payment/requests?type=view&id=' + $result->id;

                    if (!isset($final_results[$result->id])) {
                        $final_results[$result->id] = $query_result;
                    }

                    if (count($final_results) >= 10) {
                        goto return_result;
                    }
                }
            }


            return_result:
            return $final_results;
        }
    }

    private function getLimitResults($results, $limit)
    {
        return array_slice(array_values($results), 0, $limit);
    }
}
