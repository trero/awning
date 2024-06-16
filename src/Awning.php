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
        Log::info($res);

        //$this->checkDiffereceWithPastChecksum($checksum);

    }

    private function getListFilesDirectories($dir, &$results = array())
    {
        $files = scandir($dir);
    
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = array( $path, $this->calculateFileChecksum($path));
            } else if ($value != "." && $value != "..") {
                $this->getListFilesDirectories($path, $results);
                $results[] = $path;
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
        $cs = json_decode($checksum->checksum_array);
        Log::info($cs);        

    }

}