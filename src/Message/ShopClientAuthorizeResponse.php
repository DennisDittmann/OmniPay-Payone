<?php

namespace Omnipay\Payone\Message;

/**
 * Shop Authorize Response for the Client API
 * This is a transparent redirect, which involves the user being given
 * access to a form with the data this message provides.
 */

use Omnipay\Common\Message\RedirectResponseInterface;

class ShopClientAuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * A list of the CC fields required to be included in the POST form.
     * These are just for convenience, so check out their use in the documentation.
     */
    const CREDIT_CARD_FIELDS = array(
        'cardpan',
        'cardtype',
        'cardexpiredate', // Format: 'YYMM'
        'cardholder',
        'cardcvc2',
    );

    /**
     * This is a transparent redirect transaction type, where a local form
     * will POST direct to the remote gateway.
     */
    public function isTransparentRedirect()
    {
        return true;
    }

    public function isRedirect()
    {
        return true;
    }

    /**
     * The URL the form will POST to.
     */
    public function getRedirectUrl()
    {
        return $this->postUrl;
    }
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Data that must be included as hidden fields.
     */
    public function getRedirectData()
    {
        // Some of the supplied data that does not belong in the hidden form
        // fields is filtered out.

        $data = array_filter($this->getData(), function($key) {
            return !in_array($key, ['card']);
        }, ARRAY_FILTER_USE_KEY);

        return $data;
    }

    /**
     * If a card or pseudocardpan were provided in the request, then the
     * fields for that can be retrieved here.
     */
    public function getCardFieldData()
    {
        return @$this->data['card'] ?: array();
    }

    /**
     * Return false to indicate that more action is needed to complete
     * the transaction, a transparent redirect form in this case.
     */
    public function isSuccessful()
    {
        return false;
    }
}
