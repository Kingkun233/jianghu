<?php
namespace Home\Controller;

use Think\Controller;

class PushController extends Controller
{

    private $app_key = '80a75831586e07942c0f2eda';

    private $master_secret = '3b5de18b76d8722b3959765a';

    /**
     * 极光推送测试 广播
     *
     * @param unknown $message            
     */
    public function push_all($message, $type = "", $msg = "")
    {
        $client = new \JPush\Client($this->app_key, $this->master_secret);
        try {
            $push_payload = $client->push()
            ->setPlatform('all')
            ->addAllAudience()
            ->androidNotification($message, array(
                'title' => '江湖指南',
                'extras' => array(
                    'type' => $type,
                    'msg' => $msg
                )
            ))
            ->send();
            return true;
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            return false;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            return false;
        }
    }

    /**
     * 极光推送测试 个推
     *
     * @param unknown $message            
     * @param unknown $user_id            
     */
    public function push_special($message, $user_id, $type = "")
    {
        $client = new \JPush\Client($this->app_key, $this->master_secret);
        $regid = null;
        $regid = $this->getRegidByUserid($user_id);
        if ($regid) {
            try {
                $result = $client->push()
                    ->setPlatform('all')
                    ->addRegistrationId($regid)
                    ->
                // ->setNotificationAlert($message)
                androidNotification($message, array(
                    'title' => '江湖指南',
                    'extras' => array(
                        'type' => $type
                    )
                ))
                    ->send();
                // dump($result);
                return true;
            } catch (\JPush\Exceptions\APIConnectionException $e) {
                return false;
            } catch (\JPush\Exceptions\APIRequestException $e) {
                return false;
            }
        }
    }

    /**
     * 通过uid得到push_regid
     *
     * @param string $user_id            
     * @return string push_regid
     */
    public function getRegidByUserid($user_id)
    {
        $User = D('user');
        $push_regid = $User->where(array(
            "id" => $user_id
        ))->getField('push_regid');
        return $push_regid;
    }
}