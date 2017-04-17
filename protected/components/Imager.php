<?php

class Imager
{
    public function createThumbnail($imagePath, $width, $height, $faceDetection = true, $outputDirection = null)
    {
        if (!file_exists($imagePath))
            throw new Exception("Image doesn't exists.");

        if ($outputDirection == null)
            $outputDirection = $imagePath;

        if ($faceDetection) {
            $imagePath = $this->resize($imagePath, $outputDirection, $width, $height);
            $detector = new FaceDetector('detection.dat');
            $detector->faceDetect($imagePath);
            $face = $detector->getFace();

            if (!is_null($face)) {
                $imageInfo = getimagesize($imagePath);

                if ($imageInfo[0] > $imageInfo[1]) {
                    if ($face['w'] < $width || $face['w'] == $width) {
                        $coordinate = $face['x'] - (($width - $face['w']) / 2);
                        $x = ($coordinate < 0) ? 0 : $coordinate;
                        if (($x + $width) > $imageInfo[0])
                            $x = $imageInfo[0] - $width;
                        $y = 0;
                        $this->crop($imagePath, $width, $height, 100, $x, $y);
                    } elseif ($face['w'] > $width)
                        $this->crop($imagePath, $width, $height);
                } elseif ($imageInfo[0] < $imageInfo[1]) {
                    if ($face['w'] < $width || $face['w'] == $width) {
                        $x = 0;
                        $coordinate = $face['y'] - (($height - $face['w']) / 2);
                        $y = ($coordinate < 0) ? 0 : $coordinate;
                        if (($y + $height) > $imageInfo[1])
                            $y = $imageInfo[1] - $height;
                        $this->crop($imagePath, $width, $height, 100, $x, $y);
                    } elseif ($face['w'] > $width)
                        $this->crop($imagePath, $width, $height);
                } else
                    $this->crop($imagePath, $width, $height);
            } else
                $this->crop($imagePath, $width, $height);
        } else {
            $imagePath = $this->resize($imagePath, $outputDirection, $width, $height);
            $this->crop($imagePath, $width, $height);
        }
    }

    public function resize($imagePath, $outputDirection, $width, $height)
    {
        $simpleImage = new SimpleImage();
        $simpleImage->load($imagePath);

        // width and height of image in crop box
        $imageWidth = $simpleImage->getWidth();
        $imageHeight = $simpleImage->getHeight();

        //resize image to crop size
        if ($imageWidth < $imageHeight)
            $simpleImage->resizeToWidth($width);
        elseif ($imageHeight < $imageWidth)
            $simpleImage->resizeToHeight($height);
        else {
            $simpleImage->resizeToWidth($width);
            $simpleImage->save($outputDirection);
            return $outputDirection;
        }

        $simpleImage->save($outputDirection);
        return $outputDirection;
    }

    protected function crop($imagePath, $width, $height, $quality = 100, $x = null, $y = null)
    {
        $simpleImage = new SimpleImage();
        $simpleImage->load($imagePath);

        // width and height of image in crop box
        $imageWidth = $simpleImage->getWidth();
        $imageHeight = $simpleImage->getHeight();

        //resize image to crop size
        if ($imageWidth < $imageHeight) {
            if (is_null($x) && is_null($y)) {
                $srcX = 0;
                $srcY = $simpleImage->getHeight() / 2 - ($height / 2);
            } else {
                $srcX = $x;
                $srcY = $y;
            }
        } elseif ($imageHeight < $imageWidth) {
            if (is_null($x) && is_null($y)) {
                $srcX = $simpleImage->getWidth() / 2 - ($width / 2);
                $srcY = 0;
            } else {
                $srcX = $x;
                $srcY = $y;
            }
        } else
            return;

        // crop image
        $dstH = $height;
        $dstW = $width;
        $imageInfo = getimagesize($imagePath);
        $imageType = $imageInfo[2];

        if ($imageType == IMAGETYPE_PNG) {
            $thumb = imagecreatetruecolor($width, $height);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);

            $source = imagecreatefrompng($imagePath);
            imagecopyresampled($thumb, $source, 0, 0, $srcX, $srcY,
                $dstW, $dstH, $dstW, $dstH);
            sleep(1);
            imagepng($thumb, $imagePath, 9);
        } elseif ($imageType == IMAGETYPE_GIF) {
            $thumb = imagecreatetruecolor($width, $height);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);

            $source = imagecreatefromgif($imagePath);
            imagecopyresampled($thumb, $source, 0, 0, $srcX, $srcY,
                $dstW, $dstH, $dstW, $dstH);
            sleep(1);
            imagepng($thumb, $imagePath, 9);
        } else {
            $img_r = imagecreatefromjpeg($imagePath);
            $dst_r = imagecreatetruecolor($dstW, $dstH);
            imagecopyresampled($dst_r, $img_r, 0, 0, $srcX, $srcY, $dstW, $dstH, $dstW, $dstH);
            while (!file_exists($imagePath)) ;

            sleep(1);
            imagejpeg($dst_r, $imagePath, $quality);
        }
    }

    public function getImageInfo($imagePath)
    {
        $simpleImage = new SimpleImage();
        $simpleImage->load($imagePath);
        $info=array();
        $info['width'] = $simpleImage->getWidth();
        $info['height'] = $simpleImage->getHeight();
        return $info;
    }
}