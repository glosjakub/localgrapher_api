<?php

/**
 * This class can be used to send requests to Localgapher API
 *
 * Class localgrapher_api_client
 */
class localgrapher_api_client
{

    private $api_key = '';
    private $post_data = [];
    private $api_endpoints = [
        'add_enquiry' => 'https://www.localgrapher.com/wp-json/lc/v1/add/enquiry/',
        'get_cities' => 'https://www.localgrapher.com/wp-json/lc/v1/get/cities/',
        'get_photographs' => 'https://www.localgrapher.com/wp-json/lc/v1/get/photographs/'
    ];

    /**
     * Constructor, requires to pass an API key.
     *
     * localgrapher_api_client constructor.
     * @param $api_key
     */
    function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Adds a new enquiry
     */
    function add_enquiry()
    {
        $ch = curl_init();

        // check if data are not empty
        if (empty($this->post_data)) {
            return false;
        }

        // add api key to post data
        $this->post_data['api_key'] = $this->api_key;

        // all good, we can send the request
        curl_setopt($ch, CURLOPT_URL, $this->api_endpoints['add_enquiry']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->post_data));


        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $decoded_response = json_decode($server_output);
        return $decoded_response->data->status;
    }


    /**
     * Requests a list of cities
     */
    function get_cities()
    {
        $ch = curl_init();

        // add api key to post data
        $this->post_data['api_key'] = $this->api_key;

        // all good, we can send the request
        curl_setopt($ch, CURLOPT_URL, $this->api_endpoints['get_cities'] . '?api_key=' . $this->api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $server_output = curl_exec($ch);
        curl_close($ch);
        $decoded_response = json_decode($server_output);

        // we have some kind of error, return the code
        if (!empty($decoded_response->data->status)) {
            return $decoded_response->data->status;
        }

        // all good, return array of values
        return $decoded_response;
    }

    /**
     * Requests a list of photographers by city ID.
     *
     * @param $city_id
     * @return array|mixed|object
     */
    function get_photographs($city_id)
    {
        $ch = curl_init();

        // add api key to post data
        $this->post_data['api_key'] = $this->api_key;

        // all good, we can send the request
        curl_setopt($ch, CURLOPT_URL, $this->api_endpoints['get_photographs'] . $city_id . '/?api_key=' . $this->api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $server_output = curl_exec($ch);

        curl_close($ch);
        $decoded_response = json_decode($server_output);

        // we have some kind of error, return the code
        if (!empty($decoded_response->data->status)) {
            return $decoded_response->data->status;
        }

        // all good, return array of values
        return $decoded_response;
    }

    /**
     * Sets post data, accepts array of values. For the parameters, please refer to the documentation.
     *
     * @param $data
     */
    function set_post_data($data)
    {
        $this->post_data = $data;
    }
}

/* START CLIENT */
$local = new localgrapher_api_client('YOUR_API_KEY');

/* ADD ENQUIRY */
$data = [
    'client_name' => 'bob',
    'client_email' => 'test@test.cz',
    'date' => '01/17/2018',
    'city' => '343',
    'other_dates' => 'any string representing a date',
    'notes' => 'this is a note',
    'preferred_contact' => 'skype',
    'preferred_contact_detail' => 'test.test'
];
$local->set_post_data($data);
$response = $local->add_enquiry();
echo $response;

/* LIST CITIES */
$cities = $local->get_cities();
echo "<pre>";
print_r($cities);
echo "</pre>";

/* LIST PHOTOGRAPHS BY CITY ID */
$photographs = $local->get_photographs(17);
echo "<pre>";
print_r($photographs);
echo "</pre>";










