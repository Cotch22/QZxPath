<?php

namespace QZxPath;
use DOMDocument;
use DOMXPath;

class QZxPath
{
    private $baseUrl;
    private $isVerifyCode;

    public function __construct($baseUrl, $isVerifyCode)
    {
        $this->baseUrl = $baseUrl;
        $this->isVerifyCode = $isVerifyCode;
    }

    private function getCookie($data) {
        if(preg_match_all('/Set-Cookie: (.*?);/m', $data, $cookies)){
            $cookie = implode(';', $cookies[1]);
        }
        return $cookie ?? false;
    }

    private function xPath($data, $x) {
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="UTF-8">'.$data);
        $xpath = new DOMXPath($dom);
        return $xpath->query($x);
    }

    private function statusTest() {
        $home = new Request($this->baseUrl);
        $home->needHeader();
        $data = $home->send();
        $home->close();
        if($data){
            $cookie = $this->getCookie($data);
        }
        return $cookie ?? false;
    }

    public function login($encoded) {
        $cookie = $this->statusTest();
        if (!$cookie) {
            exit(69);
        }

        $loginSuc = false;

        $req_Login = new Request($this->baseUrl);
        $req_Login->needHeader();

        if ($this->isVerifyCode) {
            $req_VC = new Request($this->baseUrl . 'verifycode.servlet');

            for ($roll = 0; $roll < 3; $roll++) {
                $req_VC->setCookie($cookie);
                $req_Login->setCookie($cookie);

                $data = $req_VC->send();
                $vCode = ImgIdenfy::getVC($data);

                $loginPostData = [
                    'encoded' => $encoded,
                    'RANDOMCODE' => $vCode
                ];
                $req_Login->setUrl($this->baseUrl . 'xk/LoginToXk?encoded='.$encoded.'&RANDOMCODE='.$vCode);
                $req_Login->setPostData($loginPostData);
                $data = $req_Login->send();
                $httpInfo = $req_Login->getHttpInfo();

                if ($httpInfo['http_code'] == 200 && $httpInfo['redirect_url'] == ''){
                    $cookie = $this->getCookie($data);
                    continue;
                } elseif ($httpInfo['http_code'] == 302) {
                    $cookie = $this->getCookie($data);
                    $loginSuc = true;
                    $req_VC->close();
                    $req_Login->close();
                    break;
                }
            }
        } else {
            $loginPostData = [
                'encoded' => $encoded
            ];
            $req_Login->setUrl($this->baseUrl . 'xk/LoginToXk?encoded='.$encoded);
            $req_Login->setPostData($loginPostData);
            $data = $req_Login->send();
            $httpInfo = $req_Login->getHttpInfo();
            $req_Login->close();

            if ($httpInfo['http_code'] == 302) {
                $cookie = $this->getCookie($data);
                $loginSuc = true;
            }
        }

        return $loginSuc ? $cookie : false;
    }

    public function auth($encoded) {
        $cookie = $this->login($encoded);
        if ($cookie) {
            $req = new Request($this->baseUrl . 'framework/xsMain_new.jsp', $cookie);
            $data = $req->send();
            $req->close();

            $out = $this->xPath($data, "//div[@class='middletopttxlr']/div/div[2]");
            $i = 1;
            $main = [];
            foreach ($out as $e) {
                $main[$i] = $e->nodeValue;
                $i++;
            }
            array_shift($main);
            return [$main, $cookie];
        } else {
            return false;
        }
    }

    public function getSkd($cookie) {
        global $SKD;
        $SKD = [];

        $data = Request::justSend($this->baseUrl . 'xskb/xskb_list.do', $cookie);

        function getWeek($time)
        {
            $tmp = explode("(周)", $time);
            $weekStr = explode(',', str_replace('，', ',', $tmp[0]));
            $week = [];
            foreach ($weekStr as $item) {
                if (strpos($item, '-')) {
                    $range = explode('-', $item);
                    $start = (int)$range[0];
                    $end = (int)$range[1];
                    for ($i = $start; $i <= $end; $i++) {
                        array_push($week, $i-1);
                    }
                } else {
                    array_push($week, (int)($item - 1));
                }
            }
            return $week;
        }

        function writeSkd_4($skd, $week, $day, $jc)
        {
            for ($i = 0; $i <= 17; $i++) {
                if (in_array($i, $week)) {
                    $GLOBALS['SKD'][$i][$day][$jc] = [
                        'd' => 't',
                        't' => 2,
                        'jc' => $jc,
                        'name' => $skd[0],
                        'zc' => explode("(周)", $skd[2])[0],
                        'tc' => $skd[1],
                        'add' => $skd[3],
                        'ty' => false
                    ];
                } else {
                    $GLOBALS['SKD'][$i][$day][$jc] = [
                        'd' => 'b',
                        't' => 2
                    ];
                }
            }
        }

        function writeSkd_5($skd, $week, $day, $jc)
        {
            for ($i = 0; $i <= 17; $i++) {
                if (in_array($i, $week)) {
                    $ty = str_replace(['(', '课)'], '', $skd[1]);
                    $GLOBALS['SKD'][$i][$day][$jc] = [
                        'd' => 't',
                        't' => 2,
                        'jc' => $jc,
                        'name' => $skd[0],
                        'zc' => explode("(周)", $skd[3])[0],
                        'tc' => $skd[2],
                        'add' => $skd[4] ?? false,
                        'ty' => $ty
                    ];
                } else {
                    $GLOBALS['SKD'][$i][$day][$jc] = [
                        'd' => 'b',
                        't' => 2
                    ];
                }
            }
        }

        $out = $this->xPath($data, '//tr/td/div[2]');
        $day = 0;
        $jc = 0;
        foreach ($out as $e) {
            if ($day == 5 || $day == 6) {
                $day++;
                continue;
            }
            if ($day == 7) {
                $day = 0;
                $jc++;
            }
            if ($jc == 5) {
                break;
            }
            if (strlen($e->textContent) == 2) {
                for ($i = 0; $i <= 17; $i++) {
                    $SKD[$i][$day][$jc] = [
                        'd' => 'b',
                        't' => 2
                    ];
                }
            } else {
                $tmp_num = 0;
                $tmp = [];
                $doubleFlag = false;
                $flagSign = 0;
                foreach ($e->childNodes as $key => $t) {
                    if ($key % 2 == 0) {
                        $tmp[$tmp_num] = str_replace(['（', '）'], ['(', ')'], $t->textContent);
                        $tmp_num++;
                        if (preg_match("/----/", $t->textContent)) {
                            $doubleFlag = true;
                            $flagSign = $tmp_num;
                        }
                    }
                }
                if (strpos($tmp[0], '大学体育') !== false) {
                    writeSkd_5($tmp, getWeek($tmp[3]), $day, $jc);
                } else {
                    if ($tmp_num == 4) {
                        writeSkd_4($tmp, getWeek($tmp[2]), $day, $jc);
                    }
                    if ($tmp_num == 5) {
                        writeSkd_5($tmp, getWeek($tmp[3]), $day, $jc);
                    }
                    if ($doubleFlag) {
                        $first = array_slice($tmp, 0, $flagSign - 1);
                        $second = array_slice($tmp, $flagSign);
                        if ($flagSign == 5) {
                            writeSkd_4($tmp, getWeek($first[2]), $day, $jc);
                        }
                        if ($flagSign == 6) {
                            writeSkd_5($tmp, getWeek($first[3]), $day, $jc);
                        }
                        if (count($second) == 4) {
                            writeSkd_4($tmp, getWeek($second[2]), $day, $jc);
                        }
                        if (count($second) == 5) {
                            writeSkd_5($tmp, getWeek($second[3]), $day, $jc);
                        }
                    }
                }
            }
            $day++;
        }

        return $SKD;
    }

    public function getMark($cookie) {
        $data = Request::justSend($this->baseUrl . 'kscj/cjcx_list', $cookie);

        $out = $this->xPath($data, '//td');
        $i = 0;
        $key = 0;
        $mark = [];
        foreach ($out as $e) {
            if ($i == 16) {
                $i = 0;
                $key++;
            }
            $mark[$key][$i] = trim($e->textContent);
            $i++;
        }

        return array_reverse($mark);
    }

    public function getScore($cookie) {
        $req = new Request($this->baseUrl . 'xxwcqk/xxwcqk_idxOntx.do', $cookie);
        $data = $req->send();

        $out = $this->xPath($data,'//input[@name="ndzydm"]/@value');
        $postData = [
            'ndzydm' => $out[0]->nodeValue
        ];

        $req->setUrl($this->baseUrl . 'xxwcqk/xxwcqkOnkctx.do');
        $req->setPostData($postData);
        $data = $req->send();
        $req->close();

        $out1 = $this->xPath($data, '//table[@class="Nsb_r_list Nsb_table"][1]//tr/td');
        $out2 = $this->xPath($data, '//table[@class="Nsb_r_list Nsb_table"][2]//tr');

        $i = 0;
        $key = 0;
        $score = [];
        foreach ($out1 as $e) {
            if ($i == 5) {
                $i = 0;
                $key++;
            }
            $score[0][$key][$i] = str_replace(['（', '）'], ['(', ')'], trim($e->textContent));
            $i++;
        }

        $j = -1;
        foreach ($out2 as $e) {
            $childNum = $e->childNodes->length;
            if ($childNum == 2) {
                $j++;
                $key = 0;
                $score[2][$j] = trim($e->textContent);
            } elseif ($childNum == 16) {
                $i = 0;
                if (strlen($e->textContent) == 75) {
                    continue;
                }
                foreach ($e->childNodes as $t) {
                    if ($i == 8) {
                        $i = 0;
                        $key++;
                    }
                    if ($t->nodeName == 'td') {
                        $score[1][$j][$key][$i] = str_replace(['（', '）'], ['(', ')'], trim($t->textContent));
                        if ($i == 2) {
                            $score[1][$j][$key][8] = str_replace('(计划内)', '', $score[1][$j][$key][2]);
                        }
                        if ($i == 5 && $score[1][$j][$key][5] == '修读中') {
                            $score[1][$j][$key][9] = true;
                        }
                        $i++;
                    }
                }
            }
        }

        return $score;
    }

    public function getPJList($cookie) {
        $data = Request::justSend($this->baseUrl . 'xspj/xspj_find.do', $cookie);

        $out = $this->xPath($data, '//tr/td[7]/a/@href');
        $list = [];
        foreach ($out as $key => $e) {
            $list[$key] = $e->nodeValue;
        }

        $req = new Request($this->baseUrl, $cookie);

        $i = 0;
        $key = 0;
        $main = [];
        foreach ($list as $tmp) {
            $req->setUrl($this->baseUrl . substr($tmp, 7));
            $data = $req->send();

            $out = $this->xPath($data, '//td');
            foreach ($out as $e) {
                if ($i == 9) {
                    $i = 0;
                    $key++;
                }
                if ($i !== 8) {
                    $main[$key][$i] = $e->nodeValue;
                }
                $i++;
            }
        }
        $req->close();

        return $main;
    }

    public function doAutoPJ($cookie) {
        $data = Request::justSend($this->baseUrl . 'xspj/xspj_find.do', $cookie);

        $out = $this->xPath($data, '//tr/td[7]/a/@href');
        $list = [];
        foreach ($out as $key => $e) {
            $list[$key] = $e->nodeValue;
        }

        $req = new Request($this->baseUrl, $cookie);

        $i = 0;
        $todo = [];
        foreach ($list as $tmp) {
            $req->setUrl($this->baseUrl . substr($tmp, 7));
            $data = $req->send();

            $out = $this->xPath($data, '//tr/td[9]/a/@href');
            foreach ($out as $e) {
                $todo[$i] = $e->nodeValue;
                $i++;
            }
        }

        $main = [];
        foreach ($todo as $key => $tmp) {
            $req->setUrl($this->baseUrl . substr($tmp, 7));
            $data = $req->send();

            $out1 = $this->xPath($data, '//input/@name');
            $out2 = $this->xPath($data, '//input/@value');
            $name = [];
            $value = [];
            foreach ($out1 as $i => $e) {
                $name[$i] = $e->nodeValue;
            }
            foreach ($out2 as $i => $e) {
                $value[$i] = $e->nodeValue;
            }
            $content = 'issubmit=1&sfxyt=0';
            $sign = 0;
            for ($index = 2; $index <= sizeof($name); $index++) {
                if ($name[$index] == 'pj03id'){
                    break;
                }
                $content = $content . '&' . $name[$index] . '=' . $value[$index];
                if ($sign == 1) {
                    $index += 9;
                    $sign = 0;
                }
                if ($name[$index] == 'pj06xh'){
                    $sign = 1;
                }
            }
            $main[$key] = $content;
        }
        $req->close();

        $headers = array('Content-Type: application/x-www-form-urlencoded');
        foreach ($main as $key => $tmp) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . 'xspj/xspj_save.do');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $tmp);
            curl_exec($ch);
            curl_close($ch);
        }

        return true;
    }

    public function logout($cookie) {
        Request::justSend($this->baseUrl . 'xk/LoginToXk?method=exit&tktime='.strtotime('now'), $cookie);
        return true;
    }
}