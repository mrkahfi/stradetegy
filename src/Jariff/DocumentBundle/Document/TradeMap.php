<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 9/27/13
 * Time: 1:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\DocumentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="trade_map")
 */
class TradeMap
{

    /** @MongoDB\Id */
    private $id;

    /** @MongoDB\String */
    private $parent;

    /** @MongoDB\String */
    private $tree;




    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parent
     *
     * @param string $parent
     * @return self
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return string $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set tree
     *
     * @param string $tree
     * @return self
     */
    public function setTree($tree)
    {
        $this->tree = $tree;
        return $this;
    }

    /**
     * Get tree
     *
     * @return string $tree
     */
    public function getTree()
    {
        return $this->tree;
    }
}
