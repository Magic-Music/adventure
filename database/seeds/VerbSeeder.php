<?php

use Illuminate\Database\Seeder;
use App\Models\Verb;

class VerbSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Verb::truncate();
        
        $handle = fopen(storage_path('app/data/verbs.csv'), 'r');

        $columns = fgetcsv($handle);

        while ($row = fgetcsv($handle)) {
            $verb = array_combine($columns, $row);
            Verb::create($verb);
        }

        fclose($handle);

        dd(Verb::all()->toArray());
    }
}
