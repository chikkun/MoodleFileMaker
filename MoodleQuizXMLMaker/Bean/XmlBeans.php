<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denn
 * Date: 13/04/18
 * Time: 13:27
 * To change this template use File | Settings | File Templates.
 */

namespace Bean;

/**
 * Class TorFBeans
 * @package Bean
 *
 */
class XmlBeans {

    protected $beans = array();
    /**
     *@param TorFBean $bean
     */
    function addBean($bean) {
       $this->beans[] = $bean ;
    }

    /**
     * @return TorFBean
     *         空の場合NULLを返す。
     */
    function getBean() {
            return array_shift($this->beans) ;
    }
}