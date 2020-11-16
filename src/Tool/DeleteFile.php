<?php

namespace App\Tool;

class DeleteFile
{
    public function delete($name)
    {
        $filename = "../public/uploads/pictures/";
        if (file_exists($filename . $name)) {
            echo "Le fichier" . $name . "existe.";
            unlink($filename . $name);
        } else {
            echo "Le fichier $name n'existe pas.";
        }
    }
}
