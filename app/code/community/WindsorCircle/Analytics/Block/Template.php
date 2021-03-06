<?php
class WindsorCircle_Analytics_Block_Template extends Mage_Core_Block_Template
{
    public function renderDataAsJsonVar($var_name)
    {
        return $this->getLayout()->createBlock('windsorcircle_analytics/json')
        ->setData($this->getData())
        ->setVarName($var_name)
        ->toHtml();
    }

    public function renderDataAsJsonObject($key=false)
    {
        $data = $key ? $this->getData($key) : $this->getData();
        return $this->getLayout()->createBlock('windsorcircle_analytics/json')
        ->setData($data)
        ->setAsRawObject(true)
        ->toHtml();
    }

    public function getContextJson()
    {
        $renderer = $this->getLayout()->createBlock('windsorcircle_analytics/json')
        ->setData(array(
            'library'=> array(
                'name'=>'analytics-magento',
                'version'=>(string) Mage::getConfig()->getNode()->modules->WindsorCircle_Analytics->version
        )));
        return $renderer->toJsonString();
    }

    /**
    * Ensure safe JSON string, even for Magento systems still
    * running on PHP 5.2
    */
    public function getPropertyAsJavascriptString($prop)
    {
        $data = (string) $this->getData($prop);
        if ($prop == 'user_id' && empty($data)) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $data = (string) $customer->getId();
        }
        $data = json_encode($data);
        $data = preg_replace('%[^ $:"\'a-z>0-9_-]%six','',$data);
        return $data;
    }
}
