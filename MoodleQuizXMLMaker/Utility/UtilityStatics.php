<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/17
 * Time: 18:38
 * To change this template use File | Settings | File Templates.
 */

namespace Utility;


use JsonSchema\Constraints\Object;

class UtilityStatics
{


    /**
     * 値があれば上書きせず、ない場合は初期値としてコピーする。
     * $op → $op2 と言う方向にマージし、マージした配列を返す。
     * @param $op
     * @param $op2
     * @return mixed
     */
    public static function mergeLargerToSmaller($op, $op2)
    {
        return (object)((array)$op + (array)$op2);
    }

    /**
     * 値があれば上書きし、ない場合もコピーする.
     * $op → $op2 と言う方向にマージし、マージした配列を返す。
     * @param $op
     * @param $op2
     * @return mixed
     */
    public static function mergeSmallerToLarger($op, $op2)
    {

        return (object)array_merge((array)$op, (array)$op2);
    }

    /**
     * 値があれば上書きし、ない場合はコピーしない。
     * $arr1 → $arr2 と言う方向にマージし、マージした配列を返す。
     * @param $arr1
     * @param $arr2
     * @return mixed
     */
    public static function mergeSmallerToLargerNotCopy($arr1, $arr2)
    {
        foreach ($arr2 as $key => $value) {
            if (isset($arr1->$key)) {
                if (is_array($value)) {
                    mergeSmallerToLargerNotCopy($arr1->$key, $value);
                } else {
                    $arr1->$key = $value;
                }
            }
        }
        return $arr1;
    }

}