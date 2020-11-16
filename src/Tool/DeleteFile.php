<?php

namespace App\Tool;

class DeleteFile
{
    public function delete($name)
    {
        $filename = "../public/uploads/pictures/";
        if (file_exists($filename . $name)) {
            unlink($filename . $name);
        }
    }
}
