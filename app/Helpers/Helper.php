<?php

namespace App\Helpers;

class Helper
{
    private static $validImageTypes = ['jpeg', 'jpg', 'png', 'bmp'];
    private static $validDocumentTypes = ['pdf', 'xls', 'xlsx', 'doc', 'docx'];

    public static function validateFile($fileName, $validTypes)
    {
        $response = [
            'status' => 'error',
            'code' => 401,
            'data' => ''
        ];

        $pathParts = pathinfo($fileName->getClientOriginalName());
        $fileExtension = strtolower($pathParts['extension']);

        if (in_array($fileExtension, array_map('strtolower', $validTypes))) {
            $response = [
                'status' => 'ok',
                'code' => 200,
                'data' => $pathParts
            ];
        }

        return $response;
    }

    public static function scriptStripper($input, $ckEditor = false)
    {
        // Eğer input null ise null döndürüyoruz
        if ($input === null) {
            return null;
        }

        // Eğer input bir array ise her bir öğe için strip_tags işlemi yapılır
        if (is_array($input)) {
            return array_map(function($item) use ($ckEditor) {
                return is_string($item) ? self::scriptStripper($item, $ckEditor) : $item;
            }, $input);
        }

        // Eğer input string değilse (başka bir veri türü) direkt olarak input'u döndürüyoruz
        if (!is_string($input)) {
            return $input;
        }

        // String işlemi: ckEditor durumu kontrol edilir ve buna göre işlem yapılır
        return $ckEditor ? $input : strip_tags($input);
    }

    public static function isDocument($fileName)
    {
        return self::validateFile($fileName, self::$validDocumentTypes);
    }

    public static function isImage($fileName)
    {
        return self::validateFile($fileName, self::$validImageTypes);
    }

    public static function imageAndDocument($fileName)
    {
        $validTypes = array_merge(self::$validDocumentTypes, self::$validImageTypes);
        return self::validateFile($fileName, $validTypes);
    }


}
