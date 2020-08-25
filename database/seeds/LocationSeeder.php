<?php

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Location::truncate();
        Location::create([
            'id' => 0,
            'slug' => 'purgatory',
            'description' => 'in purgatory',
            'long_description' => "in purgatory. You shouldn't be here - you must have cheated. " .
                                    "This is a storage location to store items that are being carried ".
                                    "by characters. Also there are no exits so we can remove characters " .
                                    "to here and the can't wander off",
        ]);
        
        $handle = fopen(storage_path('app/data/locations.csv'), 'r');

        $columns = fgetcsv($handle);

        while ($row = fgetcsv($handle)) {
            $location = array_combine($columns, $row);
            Location::create($location);
        }

        fclose($handle);
    }
}
