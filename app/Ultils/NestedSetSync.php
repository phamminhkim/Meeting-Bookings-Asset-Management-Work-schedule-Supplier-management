<?php

namespace App\Ultils;

use App\Models\shared\Menu;
use Illuminate\Support\Facades\Log;

class NestedSetSync
{
    private $menus_map;
    private $current_count;

    public function __construct()
    {
        $this->menus_map = array();
        $this->current_count = 1;

        $menus = Menu::select('id', 'parent', 'sort')->orderBy('parent')->orderBy('sort')->get();
        foreach ($menus as $menu) {
            $parent_id = $menu['parent'];
            $child_id = $menu['id'];
            if (!array_key_exists($parent_id, $this->menus_map)) {
                $this->menus_map[$parent_id] = array();
            }
            $this->menus_map[$parent_id][] = $child_id;
        }
    }

    public function traverse_update($menu_id = 0)
    {
        $left = $this->current_count;
        $this->current_count++;

        $menu_childrens = $this->get_menu_childrens($menu_id);
        if ($menu_childrens) {
            foreach ($menu_childrens as $menu_child) {
                $this->traverse_update($menu_child);
            }
        }
        $right = $this->current_count;
        $this->current_count++;
        $this->update($menu_id, $left, $right);
    }

    private function get_menu_childrens($menu_id)
    {
        if (array_key_exists($menu_id, $this->menus_map)) {
            return $this->menus_map[$menu_id];
        }
        return null;
    }

    private function update($menu_id, $left, $right)
    {
        $menu = Menu::find($menu_id);
        if ($menu) {
            $menu->left = $left;
            $menu->right = $right;
            $menu->save();
        }
    }
}
