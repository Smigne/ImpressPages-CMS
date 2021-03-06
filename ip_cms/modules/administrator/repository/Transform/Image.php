<?php
    /**
     * @package   ImpressPages
     *
     *
     */

namespace Modules\administrator\repository\Transform;

abstract class Image extends Base
{

    /**
     * @param $imageFile
     * @return resource
     * @throws \Modules\administrator\repository\TransformException
     */
    protected function createImageImage($imageFile){

        $this->getMemoryNeeded($imageFile);
        $mime = $this->getMimeType($imageFile);

        switch ($mime) {
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000:
                $image = imagecreatefromjpeg(BASE_DIR.$imageFile);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif(BASE_DIR.$imageFile);
                imageAlphaBlending($image, false);
                imageSaveAlpha($image, true);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng(BASE_DIR.$imageFile);
                imageAlphaBlending($image, false);
                imageSaveAlpha($image, true);
                break;
            default:
                throw new \Modules\administrator\repository\TransformException("Incompatible type. Type detected: ".$mime, \Modules\administrator\repository\TransformException::UNKNOWN_MIME_TYPE);
        }

        return $image;
    }


    /**
     * Takes memory required to process supplied image file and a bit more for future PHP operations.
     * @param resource $imageFile
     * @return bool true on success
     */
    protected function getMemoryNeeded($imageFile){
        if (!file_exists(BASE_DIR.$imageFile)) {
            return 0;
        }
        $imageInfo = getimagesize(BASE_DIR.$imageFile);
        if(!isset($imageInfo['channels']) || !$imageInfo['channels']) {
            $imageInfo['channels'] = 4;
        }
        if(!isset($imageInfo['bits']) || !$imageInfo['bits']) {
            $imageInfo['bits'] = 8;
        }

        if (!isset($imageInfo[0])) {
            $imageInfo[0] = 1;
        }

        if (!isset($imageInfo[1])) {
            $imageInfo[1] = 1;
        }

        $memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
        if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > (integer) ini_get('memory_limit') * pow(1024, 2)) {
            $success = ini_set('memory_limit', (integer) ini_get('memory_limit')+ 10 + ceil(((memory_get_usage() + $memoryNeeded) - (integer) ini_get('memory_limit') * pow(1024, 2)) / pow(1024, 2)) . 'M');
        } else {
            $success = true;
        }
        return $success;
    }

    /**
     * @param resource $image
     * @param string $fileName
     * @param int $quality from 0 to 100
     * @return bool
     * @throws \Modules\administrator\repository\TransformException
     */
    protected function saveJpeg($image, $fileName, $quality) {
        if(!imagejpeg($image, $fileName, $quality)){
            throw new \Modules\administrator\repository\TransformException("Can't write to file: ".$fileName , \Modules\administrator\repository\TransformException::WRITE_PERMISSION);
        }
        return true;
    }

    /**
     * @param resource $image
     * @param string $fileName
     * @param int $quality - from 0 to 9
     * @return bool
     * @throws \Modules\administrator\repository\TransformException
     */
    protected function savePng($image, $fileName, $compression) {
        if (!imagepng($image, $fileName, $compression)) {
            throw new \Modules\administrator\repository\TransformException("Can't write to file: ".$fileName , \Modules\administrator\repository\TransformException::WRITE_PERMISSION);
        }
        return true;
    }






    /**
     * Get mime type of an image file
     * @param string $imageFile
     * @return int mixed
     * @throws \Modules\administrator\repository\TransformException
     */
    protected function getMimeType($imageFile) {
        $imageInfo = getimagesize(BASE_DIR.$imageFile);
        if (isset($imageInfo[2])) {
            return $imageInfo[2];
        } else {
            throw new \Modules\administrator\repository\TransformException("Incompatible type.", \Modules\administrator\repository\TransformException::UNKNOWN_MIME_TYPE);
        }

    }


    /**
     * @param resource $imageNew
     * @param string $newFile
     * @param int $quality from 0 to 100
     * @throws \Modules\administrator\repository\TransformException
     */
    protected function saveImage ($imageNew, $newFile, $quality){

        $pathInfo = pathinfo($newFile);

        switch (strtolower(isset($pathInfo['extension']) ? $pathInfo['extension'] : '')) {
            case 'png':
                    //fill transparent places with white.
                    /*$width = imagesx($imageNew);
                    $height = imagesy($imageNew);
                    $imageBg = imagecreatetruecolor($width, $height);
                    imagealphablending($imageBg, false);
                    imagesavealpha($imageBg,true);
                    imagealphablending($imageNew, true);
                    imagesavealpha($imageNew,true);
                    $color = imagecolorallocatealpha($imageBg, 255, 255, 0, 0);
                    imagefilledrectangle ( $imageBg, 0, 0, $width, $height, $color );
                    imagecopymerge($imageBg, $imageNew, 0, 0, 0, 0, $width, $height, 50);
                    */
                    self::savePng($imageNew, $newFile, 9); //9 - maximum compression. PNG is always lossless
                break;
            case 'jpg':
            case 'jpeg':
            default:
                    self::saveJpeg($imageNew, $newFile, $quality);
                break;
        }
    }

}
