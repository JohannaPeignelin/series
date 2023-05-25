<?php

namespace App\Tools;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    public function save(UploadedFile $file, string $name, string $directory) : string{
        //on pourrait utiliser un slugger qui sert a nettoyer le lien
        $newFileName = $name." - ".uniqid().".".$file->guessExtension();

        //sauvegarde du fichier sur le serveur en le renommant
        $file->move($directory,$newFileName);

        return $newFileName;
    }



}