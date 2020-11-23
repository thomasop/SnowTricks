<?php

namespace App\Tool;

class SlugBuilder
{
    public function buildSlug($title)
    {
        return strtolower(str_replace(' ', '-', $title));
    }
}
