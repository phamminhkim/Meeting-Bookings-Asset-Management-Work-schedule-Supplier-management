<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\Menu;
use App\Ultils\NestedSetSync;
use App\Ultils\Ultils;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class MenuController extends BaseController
{
    public function index()
    {
        $list = Menu::where('parent', 0)->orderBy('sort')->get();

        return view('admin.menu.index', ['list' => $list]);
    }

    public function create(Request $request)
    {
    }

    public function update_nestedtree()
    {
        $transformer =  new NestedSetSync();
        $transformer->traverse_update();

        return response('Yes', 200);
    }

    public function store(Request $request)
    {

        if ($request->id != '') {
            $menu = Menu::find($request->id);
            $arr['type'] = 'edit';
        } else {
            $menu = new Menu();
            $arr['type'] = 'add';
            $menu->parent = 0;
            $menu->sort = 0;
        }
        $menu->title = $request->label;

        $menu->icon = $request->icon;


        if ($request->link != "" && $request->link != "#") {
            if ($request->id == '') {
                if (count(Menu::where('link', $request->link)->get()) > 0) {
                    $arr['type'] = 'err';
                    $arr['error'] = 'Liên kết đã tồn tại';
                    return response()->json($arr, 200);
                }
            } else {
                if (count(Menu::where('link', $request->link)->where('link', '!=', $menu->link)->get()) > 0) {
                    $arr['type'] = 'err';
                    $arr['error'] = 'Liên kết đã tồn tại';
                    return response()->json($arr, 200);
                }
            }
        }

        if (trim($request->link) == "") {
            $menu->link = '#';
        } else {
            $menu->link = $request->link;
        }

        $menu->save();


        $arr['menu'] =
            '<li class="dd-item dd2-item" data-id="' . $menu->id . '">
        <div class="dd-handle dd2-handle">
            <i  id="icon_show' . $menu->id . '" class="normal-icon ace-icon ' . $menu->icon . ' bigger-130"></i>
            <i class="drag-icon ace-icon fa fa-arrows bigger-125"></i>
        </div>
        <div class="dd2-content">
        <span id="label_show' . $menu->id . '">' . $menu->title . '</span>
        <span class="pull-right action-buttons">/<span id="link_show' . $menu->id . '">' . $menu->link . '</span>
                <a class="edit-button" id="' . $menu->id . '" label="' . $menu->title . '" link="' . $menu->link . '" icon="' . $menu->icon . '"><i class="ace-icon fa fa-pencil blue bigger-130"></i></a>
                <a class="del-button" id="' . $menu->id . '"><i class="ace-icon fa fa-trash-o red bigger-130"></i></a></span>
        </div>
        </li>';

        $arr['label'] = $menu->title;
        $arr['link'] = $menu->link;
        $arr['icon'] = $menu->icon;
        $arr['id'] = $menu->id;
        // //json_encode($mangdata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $transformer =  new NestedSetSync();
        $transformer->traverse_update();

        return response()->json($arr, 200);
    }

    public function update_tree(Request $request)
    {
        $value = json_decode($request->data);
        $data = Ultils::parseJsonArray($value, 0);
        $arr['menu'] = $request->data;

        $i = 0;
        foreach ($data as $row) {
            $menu = Menu::find($row['id']);
            $menu->parent = $row['parentID'];
            $menu->sort = ++$i;
            $menu->save();
        }

        $returnArray = Menu::where('parent', 0)->orderBy('sort')->get();

        $transformer =  new NestedSetSync();
        $transformer->traverse_update();

        return response()->json($returnArray, 200);
    }

    public function show(Request $request)
    {
    }

    public function destroy(Request $request)
    {
        $this->recursiveDelete($request->id);

        $transformer =  new NestedSetSync();
        $transformer->traverse_update();

        return response()->json($request->id, 200);
    }

    protected function recursiveDelete($id)
    {
        $menus = Menu::where('parent', $id)->get();
        if (count($menus) > 0) {
            foreach ($menus as $key => $value) {
                $this->recursiveDelete($value->id);
            }
        }
        Menu::find($id)->delete();
    }
}
