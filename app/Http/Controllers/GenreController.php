<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genre)
    {
        //
    }

    /**
     * Get the genre id given the time duration of the track
     * @param Carbon $time
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|string
     */
    public static function get(Carbon $time) {
        $hours = $time->hour;

        if($hours === 0) {
            $minutes = $time->minute;
            $seconds = $time->second;

            // 00:00 -> 00:30
            if($minutes === 0 && $seconds <= 30) {
                return self::getIdByName("semiquaver");
            }
            // 00:31 -> 01:00
            elseif (
                ($minutes === 0 && $seconds > 30 && $seconds <= 59) ||
                ($minutes === 1 && $seconds === 0)
            ) {
                return self::getIdByName("quaver");
            }
            // 01:01 -> 02:00
            elseif (
                ($minutes === 1 && $seconds > 0 && $seconds <= 59) ||
                ($minutes === 2 && $seconds === 0)
            ) {
                return self::getIdByName("crotchet");
            }
            // 02:01 -> 03:00
            elseif (
                ($minutes === 2 && $seconds > 0 && $seconds <= 59) ||
                ($minutes === 3 && $seconds === 0)
            ) {
                return self::getIdByName("minim");
            }
            // 03:01 -> 04:00
            elseif (
                ($minutes === 3 && $seconds > 0 && $seconds <= 59) ||
                ($minutes === 4 && $seconds === 0)
            ) {
                return self::getIdByName("semibreve");
            }
            // 04:01 -> 06:00
            elseif (
                ($minutes >= 4 && $minutes <= 5 && $seconds >= 0 && $seconds <= 59) ||
                ($minutes === 6 && $seconds === 0)
            ) {
                return self::getIdByName("breve");
            }
            // 06:01 -> 10:00
            elseif (
                ($minutes >= 6 && $minutes <= 10 && $seconds >= 0 && $seconds <= 59) ||
                ($minutes === 10 && $seconds === 0)
            ) {
                return self::getIdByName("longa");
            }
            // else fallback to maxima
        }
        return self::getIdByName("maxima");
    }

    /**
     * Retrieve the genre id by its name
     * @param string $name
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|string
     */
    private static function getIdByName(string $name) {
        return Genre::where("name", $name)->first()->id;
    }

}
