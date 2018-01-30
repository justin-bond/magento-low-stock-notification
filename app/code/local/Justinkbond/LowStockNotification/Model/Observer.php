<?php

class Justinkbond_LowStockNotification_Model_Observer
{
    public function lowStockReport($observer)
    {
        $event = $observer->getEvent();
        $stockItem = $event->getItem();

        if ($stockItem->getQty() < $stockItem->getNotifyStockQty()) {
            //stock is lower than the notify amount, send email
            $product = Mage::getModel('catalog/product')->load($stockItem->getProductId());

            $body = "";

            if ($stockItem->getQty() > 0) {
                $subject = "[Notice] Low Stock Notification"; // subject text

                $body .= "{$product->getName()} :: {$product->getSku()} is low on stock!\n\n";
                $body .= "Current Qty: {$stockItem->getQty()}\n";
                $body .= "Low Stock Date: {$stockItem->getLowStockDate()}\n";
            } else {
                $subject = "[Notice] Out of Stock Notification"; // subject text

                $body .= "{$product->getName()} :: {$product->getSku()} is out of stock!\n\n";
                $body .= "Out of Stock Date: {$stockItem->getLowStockDate()}\n";
            }

            $fromEmail = ""; // sender email address
            $toEmail = ""; // recipient email address 
            // $ccEmail = ""; // cc recipient email address 
            
            $mail = new Zend_Mail();        
            $mail->setBodyText($body);
            $mail->setFrom($fromEmail);
            $mail->addTo($toEmail);
            if ($ccEmail) {
                $mail->addCc($ccEmail);
            }
            $mail->setSubject($subject);
            $mail->send();
            
        }
    }
}