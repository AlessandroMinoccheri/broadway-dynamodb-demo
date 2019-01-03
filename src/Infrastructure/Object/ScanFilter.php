<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 2018-11-30
 * Time: 18:01
 */

namespace App\Infrastructure\Object;


class ScanFilter
{
    private $json;
    private $filter;

    public function __construct(array $fields)
    {
        $this->json = '{';
        $this->filter = '';
        $firstField = true;

        foreach ($fields as $field => $value) {
            if (!$firstField) {
                $this->json .= ',';
                $this->filter .= ' and ';
            }

            $this->json .= '":' . $field . '":'. $value;
            $this->filter .=  $field . ' = :' . $field;
            $firstField = false;
        }

        $this->json .= '}';
    }

    /**
     * @return mixed
     */
    public function getJson() :string
    {
        return $this->json;
    }

    /**
     * @return mixed
     */
    public function getFilter() :string
    {
        return $this->filter;
    }
}