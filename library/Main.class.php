<?php 

class Main
{
    public static function info()
    {
        $info = [
            'docs'    => 'https://gabgallery2.readme.io/',
            'project' => 'https://github.com/CodeBrauer/gabGallery',
            'version' => self::getVersion()
        ];

        $info['version']['string'] = implode('.', $info['version']);
        $info['version']['state'] = 'dev';
        $info['version']['build'] = trim(shell_exec('git rev-parse HEAD'));

        return $info;
    }

    private static function getVersion()
    {
        return [
            'major' => 0,
            'minor' => 1,
            'patch' => 0,
        ];
    }
}