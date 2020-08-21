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
        
        $handle = fopen(storage_path('app/data/locations.csv'), 'r');

        $columns = fgetcsv($handle);

        while ($row = fgetcsv($handle)) {
            $location = array_combine($columns, $row);
            Location::create($location);
        }

        fclose($handle);
    }
}
