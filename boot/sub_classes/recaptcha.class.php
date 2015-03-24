<?php

class recaptcha
{
    /**
     * Version of this client library.
     * @const string
     */
	 
    const VERSION = 'php_1.1.1';

    /**
     * Shared secret for the site.
     * @var type string
     */
    private $secret;

    /**
     * Method used to communicate  with service. Defaults to POST request.
     * @var RequestMethod
     */
    private $requestMethod;

    /**
     * Create a configured instance to use the reCAPTCHA service.
     *
     * @param string $secret shared secret between site and reCAPTCHA server.
     * @param RequestMethod $requestMethod method used to send the request. Defaults to POST.
     */
    public function __construct($secret, RequestMethod $requestMethod = null)
    {
        if (empty($secret)) {
            throw new \RuntimeException('No secret provided');
        }

        if (!is_string($secret)) {
            throw new \RuntimeException('The provided secret must be a string');
        }

        $this->secret = $secret;

        if (!is_null($requestMethod)) {
            $this->requestMethod = $requestMethod;
        } else {
            $this->requestMethod = new ReCaptcha\RequestMethod\Post();
        }
    }

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test.
     *
     * @param string $response The value of 'g-recaptcha-response' in the submitted form.
     * @param string $remoteIp The end user's IP address.
     * @return Response Response from the service.
     */
    public function verify($response, $remoteIp = null)
    {
        // Discard empty solution submissions
        if (empty($response)) {
            $recaptchaResponse = new ReCaptcha\Response(false, array('missing-input-response'));
            return $recaptchaResponse;
        }

        $params = new ReCaptcha\RequestParameters($this->secret, $response, $remoteIp, self::VERSION);
        $rawResponse = $this->requestMethod->submit($params);
        return ReCaptcha\Response::fromJson($rawResponse);
    }
}
