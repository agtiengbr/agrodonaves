<?php

namespace AGTI\Rodonaves;

class Utils
{
    public static function removerAcentos($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }

    public static function objectToArray($object)
    {
        $objectAsArray = (array) $object;

        foreach ($objectAsArray as $key => $value) {
            if (empty($value) && $value !== 0) {
                unset($objectAsArray[$key]);
                continue;
            }

            if (stripos($key, "\0") === 0) {
                $newKey = self::fixKeyName($key);
                self::replaceKey($objectAsArray, $key, $newKey);
            }

            if (is_array($value)) {
                foreach ($value as $sub_key => $sub_value) {
                    if (is_object($sub_value)) {
                        $objectAsArray[$newKey][$sub_key] = self::objectToArray($sub_value);
                    }
                }
            }

            if (is_object($value)) {
                $objectAsArray[$newKey] = self::objectToArray($objectAsArray[$newKey]);
            }
        }

        return $objectAsArray;
    }

    public static function replaceKey(&$array, $curkey, $newkey)
    {
        if (array_key_exists($curkey, $array)) {
            $array[$newkey] = $array[$curkey];
            unset($array[$curkey]);

            return true;
        }

        return false;
    }

    public static function fixKeyName(string $oldKey): string
    {
        return substr($oldKey, strpos($oldKey, "\0", 2) + 1);
    }
}
