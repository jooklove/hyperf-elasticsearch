<?php


namespace World\HyperfElasticsearch;

use GuzzleHttp\Psr7\Response;
use Hyperf\Engine\Http\RawResponse;
use Hyperf\Guzzle\RingPHP\CoroutineHandler;

class ElasticsearchCoroutineHandler extends CoroutineHandler
{
    protected array $configs;

    /**
     * ElasticsearchCoroutineHandler constructor.
     * @param array $configs
     * @param array $options
     */
    public function __construct(array $configs = [], array $options = [])
    {
        $this->configs = $configs;
        $this->options = $options;
        parent::__construct($options);
    }

    /**
     * @param \GuzzleHttp\Psr7\Request $request
     */
    public function __invoke($request)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();
        $version = $request->getProtocolVersion();
        $scheme = $uri->getScheme();
        $ssl = $scheme === 'https';
        $body = $request->getBody() ?? '';
        $host = $uri->getHost();
        $port = $this->port($uri->getPort(),$ssl);

        $prefix = '';
        $hosts = data_get($this->configs, 'hosts', '');
        $hosts = explode('|', $hosts);
        if (count($hosts) == 1) {
            $url = parse_url($hosts[0]);
            $prefix = $url['path'] ?? '';
        }
        $path = $prefix . ltrim($uri->getPath(),'/') ?? '/';
        if ($uri->getQuery()) {
            $path .= '?' . $uri->getQuery();
        }
        $effectiveUrl = $host.'/'.$path;
        // Init Headers
        $headers = $this->initHeaders($request);
        $settings = $this->getSettings($this->options);

        $client = $this->makeClient($host, $port, $ssl);
        if (! empty($settings)) {
            $client->set($settings);
        }

        $beginTime = microtime(true);

        try {
            $raw = $client->request($method, $path, $headers, (string) $body);
        } catch (\Exception $exception) {
            throw $exception;
        }

        return $this->getResponse($raw, $beginTime, $effectiveUrl);
    }

    protected function getResponse(RawResponse $response, float $beginTime, string $effectiveUrl) :Response
    {
        return new Response($response->statusCode,$response->headers,$response->body,$response->version);
    }

    /**
     * @param $port
     * @param bool $ssl
     * @return int
     */
    protected function port($port, bool $ssl = false): int
    {
        if ($port) {
            return (int) $port;
        }

        return $ssl ? 443 : 80;
    }

    /**
     * @param \GuzzleHttp\Psr7\Request $request
     * @return array
     */
    protected function initHeaders($request)
    {
        $headers = [];
        foreach ($request->getHeaders() ?? [] as $name => $value) {
            $headers[$name] = implode(',', $value);
        }

        if ($userInfo = $request->getUri()->getUserInfo()) {
            $headers['Authorization'] = sprintf('Basic %s', base64_encode($userInfo));
        }

        return $this->rewriteHeaders($headers);
    }
}