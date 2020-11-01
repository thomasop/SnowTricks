<?php

namespace App\Tool;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $slugger;
    private $targetDirectory;


    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file){


        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move(
                $this->getTargetDirectory(),
                $newFilename
            );
        } catch (FileException $e) {
        // ... handle exception if something happens during file upload
        }
        return $newFilename;     
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}