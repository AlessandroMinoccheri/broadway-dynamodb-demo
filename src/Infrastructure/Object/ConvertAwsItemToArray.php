<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 2018-11-30
 * Time: 17:30
 */

namespace App\Infrastructure\Object;


class ConvertAwsItemToArray
{
    public static function convert($item)
    {
        if (empty($item)) return null;

        $converted = [];
        foreach ($item as $k => $v) {
            if (isset($v['S'])) {
                $converted[$k] = $v['S'];
            }
            else if (isset($v['SS'])) {
                $converted[$k] = $v['SS'];
            }
            else if (isset($v['N'])) {
                $converted[$k] = $v['N'];
            }
            else if (isset($v['NS'])) {
                $converted[$k] = $v['NS'];
            }
            else if (isset($v['B'])) {
                $converted[$k] = $v['B'];
            }
            else if (isset($v['BS'])) {
                $converted[$k] = $v['BS'];
            }
            else {
                throw new \Exception('Not implemented type');
            }
        }

        return $converted;
    }
}