<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            "semiquaver",       // semicroma    --  00:01 -> 00:30
            "quaver",           // croma        --  00:31 -> 01:00
            "crotchet",         // semiminima   --  01:01 -> 02:00
            "minim",            // minima       --  02:01 -> 03:00
            "semibreve",        // semibreve    --  03:01 -> 04:00
            "breve",            // breve        --  04:01 -> 06:00
            "longa",            // lunga        --  06:01 -> 10:00
            "maxima",           // massima      --  > 10:01
        ];

        foreach ($genres as $genre) {
            Genre::firstOrCreate(["name" => $genre]);
        }
    }
}
