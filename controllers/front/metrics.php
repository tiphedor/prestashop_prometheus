<?php
class tiphiopsprometheusmetricsModuleFrontController extends ModuleFrontController
{
    /**
     * Pretty much self-explanatory.
     */
    public function fuckOffAndAuthenticate()
    {
        header("HTTP/1.1 401 Unauthorized");
        header("WWW-Authenticate: Basic realm=\"Authenticate\"");

        die();
    }

    /**
     * Parsed the `Authorization` header in the request. Extracts the credentials part from the input `Basic XXXXX`
     */
    public function parseAuthorizationHeader($header)
    {
        $pattern = "/^Basic ([a-zA-Z0-9\/=+]{1,})$/";
        preg_match_all($pattern, $header, $matches);

        if (isset($matches[1][0])) {
            return $matches[1][0];
        }

        $this->fuckOffAndAuthenticate();
    }


    public function generateMetrics()
    {
        $metricsList = array(
            array(
                "comment" => "# HELP whatever whatever",
                "name" => "random_metric_name",
                "value" => $this->getRandomMetric()
            )
        );

        foreach ($metricsList as &$metric) {
            echo $metric['comment'] . "\n";
            echo $metric['name'] . " " . $metric["value"];
            echo "\n";
        }
    }

    public function initHeader()
    {
        $headers = getallheaders();

        $authorization = $headers['authorization'];
        if (!isset($authorization)) {
            $this->fuckOffAndAuthenticate();
        }

        $receivedCredentials = $this->parseAuthorizationHeader($authorization);
        $realCredentials = Configuration::get("TIPHIOPSPROMETHEUS_BASICAUTH_ENCODED_CREDENTIALS");

        if ($realCredentials != $receivedCredentials) {
            $this->fuckOffAndAuthenticate();
        }

        header("Content-type: text/plain");
        die($this->generateMetrics());
    }

    /**
     * Metrics are implemented below.
     */

    public function getRandomMetric()
    {
        return "4";
    }
}
