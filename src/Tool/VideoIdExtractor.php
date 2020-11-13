<?php

namespace App\Tool;

class VideoIdExtractor
{
    public function urlToId($url)
    {

        $pregMatch = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

        if ($pregMatch)
        {
            return 'https://www.youtube.com/embed/' . $match[1];
        }

        return null;
    }

}