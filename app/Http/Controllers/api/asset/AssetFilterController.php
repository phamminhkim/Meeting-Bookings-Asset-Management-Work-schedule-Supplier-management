<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetType;
use App\User;
use Illuminate\Http\Request;

class AssetFilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serach_transaction_asset_tool(Request $request)
    {
        $search = $request->get('search', '');
        $user = auth()->user();
        $result = array();
        $assets = AssetTool::query()
            ->with(['attachment_image', 'AssetType', 'AssetGroup', 'AssetType', 'AssetDetails', 'vendor', 'department', 'AssetWarehouse.group.users'])
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('asset_code', 'like', '%' . $search . '%')
                    ->orWhereHas('AssetDetails', function ($q) use ($search) {
                        $q->where('value', 'like', '%' . $search . '%');
                    });

            })
            ->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->take(10)->get();

        $result['data'] = $assets;

        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function search_asset_type(Request $request)
    {
        $query = AssetType::query()->with(['user.groups', 'AssetGroup', 'attachment_image', 'AssetTypeDetails']);
        $result = array();
        $result['data'] = array();
        try {
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            if ($request->filled('search')) {
               $query->where('name',  'like', '%' . $request->search . '%');
            }

            $type = $query->paginate(15);
            $result['data'] = $type->items();
            $result['pagination'] = [
                'current_page' => $type->currentPage(),
                'last_page' => $type->lastPage(),
                'per_page' => $type->perPage(),
                'total' => $type->total(),
            ];
            $result['success'] = "1";

        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

}
