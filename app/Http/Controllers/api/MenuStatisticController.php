<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\payment;
use App\Models\payment\Payrequest;
use App\Models\shared\DocumentType;
use App\Models\shared\Menu;
use App\Models\shared\MenuStatistic;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuStatisticController extends Controller
{
    public function get(Request $request)
    {
        $all_statistics = MenuStatistic::all();

        $pending_counts = array();

        foreach ($all_statistics as $statistic) {
            $class = $statistic->menu_class;
            $function = $statistic->menu_class_function;
            $parameter = $statistic->menu_parameter;

            try {
                $controller = new $class();

                $pending_count = $controller->{$function}($parameter);
                if ($pending_count > 0) {
                    $menu_id = Menu::where('link', $statistic->menu_link)->first();
                    if ($menu_id) {
                        $pending_counts[$menu_id->id] = $pending_count;

                        $array_parents = Ultils::getMenuTreeTopWithoutMe_NestedSet($menu_id);
                        foreach ($array_parents as $parent_menu) {
                            if (isset($pending_counts[$parent_menu->id])) {
                                $pending_counts[$parent_menu->id] += $pending_count;
                            } else {
                                $pending_counts[$parent_menu->id] = $pending_count;
                            }
                        }
                    }
                }
                
                //code...
            } catch (\Throwable $th) {
                dd($th);
            }
        }
      
        $result = array();
        $result['pending_counts'] = $pending_counts;

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
