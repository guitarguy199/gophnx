<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
/**
 * This file contains the customers endpoint for MailWizzApi PHP-SDK.
 * 
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link http://www.mailwizz.com/
 * @copyright 2013-2015 http://www.mailwizz.com/
 */
 
 
/**
 * MailWizzApi_Endpoint_Customers handles all the API calls for customers.
 * 
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @package MailWizzApi
 * @subpackage Endpoint
 * @since 1.0
 */
class Thrive_Dash_Api_Sendreach_Customers extends Thrive_Dash_Api_Sendreach
{
    /**
     * Create a new mail list for the customer
     * 
     * The $data param must contain following indexed arrays:
     * -> customer
     * -> company
     * 
     * @param array $data
     * @return Thrive_Dash_Api_Sendreach_Response
     */
    public function create(array $data)
    {
        if (isset($data['customer']['password'])) {
            $data['customer']['confirm_password'] = $data['customer']['password'];
        }
        
        if (isset($data['customer']['email'])) {
            $data['customer']['confirm_email'] = $data['customer']['email'];
        }
        
        if (empty($data['customer']['timezone'])) {
            $data['customer']['timezone'] = 'UTC';
        }
        
        $client = new Thrive_Dash_Api_Sendreach_Client(array(
            'method'        => Thrive_Dash_Api_Sendreach_Client::METHOD_POST,
            'url'           => $this->config->getApiUrl('customers'),
            'paramsPost'    => $data,
        ));
        
        return $response = $client->request();
    }
}