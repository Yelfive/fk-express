<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2017-05-16
 */

namespace fk\express;

use Curl\Curl;

class ExpressHelper
{

    protected $curl;

    public function __construct()
    {
        $this->curl = new Curl();
    }

    public function query($postSn, $expressName = '')
    {
        $names = [];
        if ($expressName) $names = [$expressName];
        foreach ($this->getExpressNamesBySn($postSn) as $info) {
            $names[] = $info['comCode'];
        }
        foreach ($names as $name) {
            $data = $this->_tryQuerying($postSn, $name);
            if ($data) break;
        }

        return empty($data) ? [] : $data;
    }

    private function _tryQuerying($postSn, $expressName): array
    {
        $url = "http://www.kuaidi100.com/query?type=$expressName&postid=$postSn&id=1&valicode=&temp=0.09561791346856974";

        $response = $this->curl->get($url)->response;
        return $response ? json_decode($response, true) : [];
    }

    public function getExpressNamesBySn($sn)
    {
        $url = "http://www.kuaidi100.com/autonumber/autoComNum?text=$sn";
        $this->curl->get($url);
        $response = $this->curl->response;
        $data = json_decode($response, true);
        return $data['auto'] ?? [];
    }

    /**
     * @return static
     */
    public function instance()
    {
        return new static;
    }

}