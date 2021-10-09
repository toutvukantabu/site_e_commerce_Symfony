<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $bag;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger, ParameterBagInterface $bag)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->bag = $bag;
    }


    public function setEntityTargetUploads($className)
    {
        if ($this->bag->get($className)) {
            $this->targetDirectory =  $this->bag->get($className);
        }
    }


    public function upload(UploadedFile $file)
    {

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->targetDirectory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $path_part = pathinfo($this->targetDirectory);
        return  '/' . $path_part['basename'] . '/' . $fileName;
    }
}
