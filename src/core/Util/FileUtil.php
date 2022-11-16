<?php

namespace Core\Util;

use Core\Exception\FileWriteException;

class FileUtil {
    public static function mkdir($filepath)
    {
        
        $path = $filepath;
        if (file_exists($path)) {
            if (is_file($path)){
                $path = dirname($path);
            }
        } else {
            mkdir($path, 0755, true);
        }
        if (!is_writable($path)) {
            throw new FileWriteException();
        }
        return $path;
    }

}