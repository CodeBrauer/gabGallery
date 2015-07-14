<?php

/**
* Main Class for the main functions of gabGallery2
*/
class ImageFiles
{
    const GG_IMAGE_DIR = 'data/images/'; # don't forget the trailing slash!

    public function getFiles()
    {
        if (!file_exists(self::GG_IMAGE_DIR)) {
            mkdir(self::GG_IMAGE_DIR);
            if (!file_exists(self::GG_IMAGE_DIR)) {
                throw new Exception("Image dir not found", 1);
            }
        }

        $files = glob( self::GG_IMAGE_DIR.'*.{jpg,png,gif,webp}', GLOB_BRACE );

        foreach ($files as $key => $filepath) {
            $files[$key] = $this->getFileInfo($filepath);
        }

        return $files;
    }

    private function getFileInfo($filepath)
    {
        $filename         = pathinfo($filepath, PATHINFO_FILENAME);
        $info             = $this->getTags($filename);
        $info['filepath'] = $filepath;
        $info['info']     = getimagesize($filepath);
        $info['uploaded'] = filemtime($filepath);

        $protocol    = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $info['url'] = $protocol . $_SERVER['HTTP_HOST'] . '/' .  $filepath;

        return $info;
    }

    private function getTags($filename) {
        $parts = explode('_', $filename);
        $title = str_replace('+', ' ', $parts[0]);
        $tags  = explode('-', $parts[1]);
        $id    = (int)$parts[2];

        return [
            'id'    => $id,
            'title' => $title,
            'tags'  => $tags,
        ];
    }
}