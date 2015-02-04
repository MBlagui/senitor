<?php namespace Ballen\Senitor\Handlers;

use Ballen\Senitor\Entities\Transmission;
use GuzzleHttp\Client;

class XmwsRequest
{

    /**
     * Guzzle HTTP client instance.
     * @var \GuzzleHttp\Client
     */
    protected $http_client;

    /**
     * Custom options for the cURL/Guzzle request.
     * @var array
     */
    protected $options = [];

    /**
     * The API request body (XML string)
     * @var string 
     */
    protected $request_message;

    public function __construct(Transmission $request, array $optional_headers = [])
    {
        $this->request_message = $request;
        if (!is_null($optional_headers)) {
            $this->setOptions($optional_headers);
        }
    }

    /**
     * Set additonal options for the Guzzle client request.
     * @param array $options
     */
    private function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Get additonal options for the Guzzle client request.
     * @return array
     */
    private function getOptions()
    {
        return $this->options;
    }

    /**
     * Initiates the HTTP client object of which will be used to make the API requests.
     * @return void
     */
    private function initClient()
    {
        $this->http_client = new Client([
            'base_url' => $this->request_message->getTarget() . '/api/',
            'defaults' => $this->getOptions(),
        ]);
    }

    /**
     * Retrieves the Sentora module of which the client is going to request actions
     * using the modules 'webservice.ext.php' controller.
     * @return string
     */
    private function requestUri()
    {
        return $this->request_message->getModule();
    }

    /**
     * Sends the API request to the server and retrieves the response.
     */
    public function send()
    {
        $this->initClient();
        $repsonse = $this->http_client->post($this->requestUri(), [
            'body' => $this->request_message,
            'allow_redirects' => true,
        ]);
        die(var_dump($repsonse));
    }
}