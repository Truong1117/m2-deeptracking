<?php

namespace Commercers\DeepTracking\Service\Api\Dhl;

use GuzzleHttp\Client;

class ShipmentTracking
{

    /**
     * Action get piece
     */
    const OPERATION_GET_PIECE = 'd-get-piece';

    /**
     * Action get piece detail
     */
    const OPERATION_GET_PIECE_DETAIL = 'd-get-piece-detail';

    /**
     * Action get signature
     */
    const OPERATION_SIGNATURE = 'd-get-signature';

    /**
     * Action status for public user
     */
    const OPERATION_STATUS_PUBLIC = 'get-status-for-public-user';

    /**
     * @var Credentials
     */
    protected $credentials;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @param string $pieceNumber
     * @param string $language
     *
     * @return array
     */
    public function getDetails($pieceNumber, $language = RequestBuilder::LANG_EN)
    {
        $data = $this->call(static::OPERATION_GET_PIECE, $pieceNumber, $language);
        $array = $this->getArray($data);

        return $array;
    }

    /**
     * @param string $pieceNumber
     * @param string $language
     *
     * @return array
     */
    public function getDetailsAndEvents($pieceNumber, $language = RequestBuilder::LANG_EN)
    {
        $data = $this->call(static::OPERATION_GET_PIECE_DETAIL, $pieceNumber, $language);
        $array = $this->getArray($data);
        $events = @$array['data']['data']['data'];

        return ['details' => $array['data']['@attributes'], 'events' => !empty($events) ? $this->getEvents($events) : []];
    }

    /**
     * @param string $pieceNumber
     * @param string $language
     *
     * @return array
     */
    public function getSignature($pieceNumber, $language = RequestBuilder::LANG_EN)
    {
        $data = $this->call(static::OPERATION_SIGNATURE, $pieceNumber, $language);
        $array = $this->getArray($data);

        return $array['data']['@attributes'];
    }

    /**
     * @param string $pieceNumber
     * @param string $language
     *
     * @return array
     */
    public function getPublicDetails($pieceNumber, $language = RequestBuilder::LANG_EN)
    {
        $data = $this->callPublic(static::OPERATION_STATUS_PUBLIC, $pieceNumber, $language);
        $array = $this->getArray($data);
        $events = @$array['data']['data']['data'];

        return ['details' => $array['data']['data']['@attributes'], 'events' => !empty($events) ? $this->getEvents($events) : []];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getEvents($data)
    {
        foreach ($data as $event) {
            $events[] = $event['@attributes'];
        }

        return array_reverse($events);
    }

    /**
     * @param string $operation
     * @param string $pieceCode
     * @param string $language
     *
     * @return string
     */
    private function call($operation, $pieceCode, $language = RequestBuilder::LANG_EN)
    {
        $request = RequestBuilder::createRequestXML($operation, $this->credentials->getTntUser(), $this->credentials->getTntPassword(), $language, $pieceCode);
        $client = new Client();
        $res = $client->request(
            'GET', $this->credentials->getCigEndpoint() . '?xml=' . urlencode($request), [
            'auth' => [$this->credentials->getCigUser(), $this->credentials->getCigPassword()]
        ]
        );

        return $res->getBody();
    }

    /**
     * @param string $operation
     * @param string $pieceCode
     * @param string $language
     *
     * @return string
     */
    private function callPublic($operation, $pieceCode, $language = RequestBuilder::LANG_EN)
    {
        $request = RequestBuilder::createRequestPublicXML($operation, $this->credentials->getTntUser(), $this->credentials->getTntPassword(), $language, $pieceCode);
        $client = new Client();
        $res = $client->request(
            'GET', $this->credentials->getCigEndpoint() . '?xml=' . urlencode($request), [
            'auth' => [$this->credentials->getCigUser(), $this->credentials->getCigPassword()]
        ]
        );

        return $res->getBody();
    }

    /**
     * @param string $xml
     *
     * @return array
     */
    private function getArray($xml)
    {
        $xml = simplexml_load_string($xml);
        $json = json_encode($xml);

        return json_decode($json, true);
    }
}
