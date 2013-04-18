<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denn
 * Date: 13/04/18
 * Time: 13:26
 * To change this template use File | Settings | File Templates.
 */

namespace Bean;

/**
 * Class AbstractBeans
 * @package Bean
 *
 * @param array $beans
 */
abstract class AbstractBeans {

    protected $beans = array();

    function getBean(){}
    function addBean(){}

}