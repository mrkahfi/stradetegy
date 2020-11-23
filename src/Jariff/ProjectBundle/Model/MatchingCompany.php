<?php

namespace Jariff\ProjectBundle\Document;


class MatchingCompany {

    protected $name;

    
	public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Set product_desc
     *
     * @param string $product_desc
     * @return self
     */
    public function setProductDesc($product_desc)
    {
        $this->product_desc = $product_desc;
        return $this;
    }


    /**
     * Get id
     *
     * @return product_desc $product_desc
     */
    public function getProductDesc()
    {
        return $this->product_desc;
    }

}