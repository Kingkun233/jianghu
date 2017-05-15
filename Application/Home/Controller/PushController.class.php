<?php
namespace Home\Controller;

use Think\Controller;

class PushController extends Controller
{
    private $app_key = '99ce5c7353f8c6a08901a3cb';
    private $master_secret = '66ba2e49c214778dcdb48780';
    /**
     * 极光推送测试 广播
     * @param unknown $message
     */
    public function push_all($message)
    {
        $client= new \JPush\Client($this->app_key, $this->master_secret);
        $push_payload = $client->push()
            ->setPlatform('all')
            ->addAllAudience()
            ->setNotificationAlert($message);
        try {
            $response = $push_payload->send();
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            print $e;
        }
    }

    /**
     * 极光推送测试 个推
     * @param unknown $message
     * @param unknown $user_id
     */
    public function push_special($message,$user_id)
    {
        $client= new \JPush\Client($this->app_key, $this->master_secret);
        $regid=null;
        $regid=$this->getRegidByUserid($user_id);
        if($regid){
            try {
                $result = $client->push()
                ->setPlatform('all')
                ->addRegistrationId($regid)
                ->setNotificationAlert($message)
                ->send();
                // dump($result);
            } catch (\JPush\Exceptions\APIConnectionException $e) {
                print $e;
            } catch (\JPush\Exceptions\APIRequestException $e) {
                print $e;
            }
        }
    }
    /**
     * 通过uid得到push_regid
     * @param string $user_id
     * @return string push_regid
     */
    public function getRegidByUserid($user_id){
        $User=D('user');
        $push_regid=$User->where(array("id"=>$user_id))->getField('push_regid');
        return $push_regid;
    }
}