<?php

namespace App\Tool;

use App\Entity\Trick;
use App\Entity\Video;
use App\Tool\VideoIdExtractor;

class VideoFactory
{
    public static function set($videosCollection, Trick $trick) {
        $videoIdExtractor = new VideoIdExtractor();

        foreach ($videosCollection as $b => $video) {
            
            /** @var Video $video */
            $videos[] = $video->getUrl();
            //dd($video->getUrl());
            $video->setTrickId($trick);
            $video->setUrl($videoIdExtractor->urlToId($video->getUrl()));
           // dd($videoIdExtractor->urlToId($video->getUrl()));
        }
    }

}