<?php

namespace Trero\Awning;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Trero\Awning\Models\AwningChecksum;

class Awning
{
    public function checkSum()
    {
        Log::info(base_path());
        // https://stackoverflow.com/questions/55018704/how-to-specify-folder-for-laravel-migrations
        $res = $this->getListFilesDirectories(base_path());
        $checksum = new AwningChecksum;
        $checksum->checksum_array = json_encode($res);
        $checksum->save();
        //Log::info(json_decode($checksum->checksum_array));

        $this->checkDiffereceWithPastChecksum($checksum);

    }

    private function getListFilesDirectories($dir, &$results = array())
    {
        $files = scandir($dir);
        $count= 0;

        foreach ($files as $key => $value) {

            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

            if (!is_dir($path)) {

                $results[] = array(
                    'path' => $path,
                    'checksum' => $this->calculateFileChecksum($path)
                );

            } else if ($value != "." && $value != "..") {

                $this->getListFilesDirectories($path, $results);
                $results[] = array('dir' => $path);
            }
        }

        return $results;
    }

    private function calculateFileChecksum($filename) 
    {
        try {
            if (!file_exists($filename)) {
                throw new Exception("File does not exist.");
            }
    
            $checksum = md5_file($filename);
    
            if ($checksum === false) {
                throw new Exception("Error calculating MD5 checksum.");
            }
    
            return $checksum;
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }

    protected function checkDiffereceWithPastChecksum(AwningChecksum $checksum)
    {
        $secondLast = AwningChecksum::orderBy('created_at', 'desc')->skip(1)->take(1)->first();
        $arsl = json_decode($secondLast->checksum_array);
        $cs = json_decode($checksum->checksum_array, false);

        //Log::info($checksum->checksum_array);
        foreach ($cs as $item) {
            if (property_exists($item, 'path')) {
                foreach ($arsl as $value) {
                    if (property_exists($value, 'path') && $item->path == $value->path) {
                        Log::info($value->path);
                        $value->checksum != $item->checksum ? Log::info('diverso') : Log::info('uguale');
                    }
                }
                //Log::info($item->path);
            }
            /*if (is_array($item) && array_key_exists('1', $item)) {
                if (!is_dir($item[0])) {
                    //in_array($item[0], $arsl) ? Log::info('presente') : '';
                    //array_search( $item[0], array_column($cs, 1)) ? Log::info('presente') : Log::info('non');
                }
            } else {

            }*/

            //Log::info($item);
        }

    }

}