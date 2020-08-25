<?php

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Item::truncate();
        
        $handle = fopen(storage_path('app/data/items.csv'), 'r');

        $columns = fgetcsv($handle);

        while ($row = fgetcsv($handle)) {
            $item = array_combine($columns, $row);

            //Can the player take the item
            $item['takeable'] = ($item['takeable'] == 'false') ? false : true;

            //Should the item be described during the 'look' command. Normally it would be, but if a none-takable
            //item is mentioned as part of a room description it can be skipped during the listing of items visible
            $item['describe_look'] = ($item['describe_look'] == 'false') ? false : true;
            
            Item::create($item);
        }

        fclose($handle);
    }
}
