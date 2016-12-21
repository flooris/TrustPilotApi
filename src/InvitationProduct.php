<?php


namespace Flooris\Trustpilot;


class InvitationProduct
{

    public $product_uri;
    public $image_uri;
    public $name;
    public $sku;
    public $gtin = '';
    public $mpn = '';
    public $brand = '';

    /**
     * InvitationProduct constructor.
     *
     * @param $product_uri
     * @param $image_uri
     * @param $name
     * @param $sku
     * @param string $gtin
     * @param string $mpn
     * @param string $brand
     */
    public function __construct($product_uri, $image_uri, $name, $sku, $gtin = '', $mpn = '', $brand = '')
    {
        $this->product_uri = $product_uri;
        $this->image_uri = $image_uri;
        $this->name = $name;
        $this->sku = $sku;
        $this->gtin = $gtin;
        $this->mpn = $mpn;
        $this->brand = $brand;
    }
}