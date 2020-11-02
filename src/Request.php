<?php

namespace QZxPath;

class Request
{
    private $req;
    private $isPost = false;

    public function __construct($url, $cookie = false, $postData = false)
    {
        $req = curl_init();
        curl_setopt($req, CURLOPT_URL, $url);
        curl_setopt($req, CURLOPT_HEADER, false);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        if ($cookie) {
            curl_setopt($req, CURLOPT_COOKIE, $cookie);
        }
        if ($postData) {
            $this->isPost = true;
            curl_setopt($req, CURLOPT_POST, true);
            curl_setopt($req, CURLOPT_POSTFIELDS, $postData);
        }

        $this->req = $req;
    }

    public function setUrl($url)
    {
        curl_setopt($this->req, CURLOPT_URL, $url);
    }

    public function setCookie($cookie)
    {
        curl_setopt($this->req, CURLOPT_COOKIE, $cookie);
    }

    public function setPostData($postData)
    {
        if (!$this->isPost) {
            curl_setopt($this->req, CURLOPT_POST, true);
        }
        curl_setopt($this->req, CURLOPT_POSTFIELDS, $postData);
    }

    public function needHeader() {
        curl_setopt($this->req, CURLOPT_HEADER, true);
    }

    public function send() {
        return curl_exec($this->req);
    }

    public function close() {
        curl_close($this->req);
    }

    public function getHttpInfo() {
        return curl_getinfo($this->req);
    }

    public static function justSend($url, $cookie = false) {
        $req = new self($url, $cookie);
        $data = $req->send();
        $req->close();
        return $data;
    }
}