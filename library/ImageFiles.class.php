<?php

/**
* Main Class for the main functions of gabGallery2
*/
class ImageFiles
{
    const GG_IMAGE_DIR = 'data/images/'; # don't forget the trailing slash!

    public static function getFiles()
    {
        if (!file_exists(self::GG_IMAGE_DIR)) {
            mkdir(self::GG_IMAGE_DIR);
            if (!file_exists(self::GG_IMAGE_DIR)) {
                throw new Exception("Image dir not found", 1);
            }
        }

        $files = self::getFilePathnames();

        foreach ($files as $key => $filepath) {
            $files[$key] = self::getFileInfo($filepath);
        }

        return $files;
    }

    public static function getDetail($id, $mode)
    {
        $imagine = new Imagine\Imagick\Imagine();

        $filename = self::getPathName($id);
        if ($mode == 'basic') {
            return self::getFileInfo($filename);
        } else if ($mode == 'exif') {
            // @todo
        }
        
    }

    public static function getMime($id)
    {
        $filename = self::getPathName($id);
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mime     = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $mime;
    }

    public static function getPathName($id)
    {
        foreach (self::getFilePathnames() as $value) {
            $fid = (int)explode('_', basename($value))[0];
            if ($fid === (int)$id) {
                return $value;
            }
        }
    }

    private static function getFilePathnames()
    {
        return glob( self::GG_IMAGE_DIR.'*.{jpg,png,gif,webp}', GLOB_BRACE );
    }

    private static function getFileInfo($filepath)
    {
        $filename         = pathinfo($filepath, PATHINFO_FILENAME);
        $info             = self::getTags($filename);
        $info['filepath'] = $filepath;
        $info['info']     = getimagesize($filepath);
        $info['uploaded'] = filemtime($filepath);

        // rename array keys
        $info['info']['width']        = $info['info']['0']; unset($info['info']['0']);
        $info['info']['height']       = $info['info']['1']; unset($info['info']['1']);
        $info['info']['gd_imagetype'] = $info['info']['2']; unset($info['info']['2']);
        $info['info']['html']         = $info['info']['3']; unset($info['info']['3']);

        ksort($info['info']);

        $protocol    = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $info['url'] = $protocol . $_SERVER['HTTP_HOST'] . '/' .  $filepath;

        return $info;
    }

    private static function getTags($filename)
    {
        $parts = explode('_', $filename);
        
        $id    = (int)$parts[0];
        $title = str_replace('+', ' ', $parts[1]);
        $tags  = explode('-', $parts[2]);

        return [
            'id'    => $id,
            'title' => $title,
            'tags'  => $tags,
        ];
    }
}