<?php

class System
{
    public static function getFolderFiles($pathFolder, $withSubFolders = FALSE) {
        $files = scandir($pathFolder);
        $output = [];

        foreach($files as $file) {
            if($file == '.' || $file == '..') continue;
            $pathFile = "{$pathFolder}/{$file}";

            if(is_dir($pathFile)) {
                $filesFolder = self::getFolderFiles($pathFile);
                $output = array_merge($output, $filesFolder);
            }
            else {
                array_push($output, str_replace('\\', '/', $pathFile));
            }
        }
        
        return $output;
    }
}