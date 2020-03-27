<?php

namespace App\Repositories;

use App\Item;

class ItemRepository {

    /**
     * List all items order by name.
     * 
     * @return array
     */
    public function list () {
        return Item::orderBy('name', 'asc')->get();
    }

    /**
     * Get the item of given ID.
     * 
     * @param  $id
     * 
     * @return Item
     */
    public function get ($id) {
        return Item::find($id);
    }

    /**
     * Create new item.
     * 
     * @param string  $name
     * @param string  $color
     * @param integer  $length
     * 
     * @return Item
     */
    public function create ($name, $color, $length) {
        $item = Item::create([
            'name' => $name,
            'color' => $color,
            'length' => intval($length),
        ]);
        $item->save();
        $item->refresh();
        return $item;
    }

    /**
     * Update the item.
     * 
     * @param  $id
     * @param string  $name
     * @param string  $color
     * @param integer  $length
     * 
     * @return boolean
     */
    public function update ($id, $name, $color, $length) {
        $item = Item::find($id);
        if ($item) {
            $item->name = $name;
            $item->color = $color;
            $item->length = intval($length);
            $item->save();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Delete the item.
     * 
     * @param  $id
     * 
     * @return boolean
     */
    public function delete ($id) {
        $item = Item::find($id);
        if ($item) {
            $item->delete();
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
