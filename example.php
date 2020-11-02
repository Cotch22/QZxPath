<?php

require_once 'src/QZxPath.php';
use QZxPath\QZxPath;

$JW = new QZxPath('http://jwxt.xxx.edu.cn/jsxsd/', 1);

if (!$_GET['act']) {
    exit('拒绝访问');
} elseif($_POST) {
    $act = $_GET['act'];
    $uid = $_POST['id'] ?? false;
    $encoded = $_POST['encoded'] ?? false;
    $logined = $_POST['logined'] ?? false;
    $res = new Res();
    $coo = new Coo(dirname(__FILE__)."/tmp/coo-".$uid.".txt");

    if ($logined == 'true') {
        $cookie = $coo->getCookie();
    } elseif ($logined == 'false' && $act !== 'login') {
        $loginRes = $JW->login($encoded);
        if ($loginRes) {
            $cookie = $loginRes;
            $coo->setCookie($loginRes);
        } else {
            $res->res_err(12);
            exit;
        }
    }

    switch ($act) {
        case 'login' : {
            if (!$encoded) {
                $res->res_err(9);
                break;
            }

            $loginRes = $JW->login($encoded);
            if ($loginRes) {
                $res->res_suc($loginRes);
                $coo->setCookie($loginRes);
            } else {
                $res->res_err(12);
            }
            break;
        }
        case 'getSkd' : {
            $skd = $JW->getSkd($cookie);
            $res->res_suc($skd);
            break;
        }
        case 'getMark' : {
            $mark = $JW->getMark($cookie);
            $res->res_suc($mark);
            break;
        }
        case 'getScore' : {
            $score = $JW->getScore($cookie);
            $res->res_suc($score);
            break;
        }
        case 'getPJList' : {
            $pjList = $JW->getPJList($cookie);
            $res->res_suc($pjList);
            break;
        }
        case 'doAutoPJ' : {
            $autoPJRes = $JW->doAutoPJ($cookie);
            $res->res_suc($autoPJRes);
            break;
        }
        case 'logout' : {
            $logoutRes = $JW->logout($cookie);
            $res->res_suc($logoutRes);
            break;
        }
        default: {
            $res->res_err(99, '嘻嘻嘻！');
            break;
        }
    }
}


class Res {
    private $errText = [
        9   => '参数错误',
        11  => '教务系统暂时无法访问',
        12  => '登录失败',
        13  => '未知错误'
    ];

    public function res_suc($data) {
        echo json_encode([
            'code' => 0,
            'data' => $data
        ]);
    }

    public function res_err($code, $tip = false) {
        echo json_encode([
            'code' => $code,
            'tip' => $tip ?: $this->errText[$code],
        ]);
    }
}

class Coo {
    private $dir;

    public function __construct($dir) {
        $this->dir = $dir;
    }

    public function setCookie($cookie) {
        $cookie_file = fopen($this->dir, 'w');
        fwrite($cookie_file, $cookie);
        fclose($cookie_file);
    }

    public function getCookie() {
        return file_get_contents($this->dir);
    }
}