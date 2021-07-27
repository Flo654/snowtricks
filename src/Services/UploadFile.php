<?php
namespace App\Services;


use DateTime;

use Symfony\Component\HttpFoundation\File\Exception\FileException;



class UploadFile 
{
    protected $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function uploadPictures($form, $trick){

        $images = $form->get('pictures');
        
        foreach ($images as $image) {
            $filename = $image->get('filename')->getData();
            $originalFilename= pathinfo($filename->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' .uniqid().'.'.$image->get('filename')->getData()->guessExtension();
                
            try {
                $filename->move(
                    $this->getTargetDirectory(),
                    $newFilename
                );
            } catch (FileException $exception) {
                // ... handle exception if something happens during file upload
            }
            $picture = $image->getdata()
                ->setFilename($newFilename)
                ->setCreatedAt(new DateTime('NOW'))
                ->setUpdatedAt(new DateTime('NOW'))
            ;
            $trick->addPicture($picture);
        }
        
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}