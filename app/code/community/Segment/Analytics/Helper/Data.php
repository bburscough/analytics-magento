<?php
/**
 * Segment.io
 *
 * @category    Segment.io Ext
 * @package     Segment_Analytics
 * @author      Segment.io
 * @copyright   Copyright © 2014 Segment.io
 */

class Segment_Analytics_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH_SEGMENTIO_ENABLED = 'segment_analytics/general/enabled';

    /**
     * Wrapper for getting a configuration value
     *
     * @param string $path
     * @param int $storeId
     * @return Mage_Core_Model_Config
     */
    public function getConfig($path, $storeId = null)
    {
        $config = Mage::getConfig($path, $storeId);

        return $config;
    }

    /**
     * Get country by IP if user is not logged in
     *
     * @return string
     */
    public function getCountry()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        $result  = "Unknown";
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        $ipData = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

        if ($ipData && $ipData->geopluginCountryName != null) {
            $result = $ipData->geopluginCountryName;
        }

        return $result;
    }

    /**
     * Get category name for product
     *
     * @param $itemId
     * @return string
     */
    public function getCategoryItemProduct($itemId)
    {
        $product = Mage::getModel('catalog/product')->load($itemId);
        $categoryIds = $product->getCategoryIds();

        $categoryName = Mage::getModel('catalog/category')->load($categoryIds[0])->getName();

        return $categoryName;
    }

    /**
     * Get details of current viewed product
     *
     * @param $product
     * @return array
     */
    public function getProductData($product)
    {
        if ($product->getId()) {
            $productData = array(
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => $product->getFinalPrice(),
                'category' => $this->getCategoryItemProduct($product->getId())
            );
            return $productData;
        } else {
            return NULL;
        }
    }
}

