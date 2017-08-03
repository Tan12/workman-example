<?php
use \GatewayWorker\Lib\Gateway;
$online = array(); //存储在线用户的数组
class Events{
  /*
  * Events.php中定义5个事件回调方法，
  * onWorkerStart businessWorker进程启动事件（一般用不到）
  * onConnect 连接事件(比较少用到)
  * onMessage 消息事件(必用)
  * onClose 连接断开事件(比较常用到)
  * onWorkerStop businessWorker进程退出事件（几乎用不到）
  */

  /**
  * 当客户端连接时触发
  * 如果业务不需此回调可以删除onConnect
  * int $client_id 连接id
  */
  public static function onConnect($client_id){
    // 向当前client_id发送数据
    Gateway::sendToClient($client_id, "Hello $client_id");
    // 向所有人发送
    //Gateway::sendToAll("$client_id login");
  }

  /**
  * 当客户端发来消息时触发
  * int $client_id 连接id
  * string $message 具体消息
  */
  public static function onMessage($client_id, $message){
    global $online;
    if($message !== 'userlink'){
      $message = json_decode($message);
      $message->time = date('Y-m-d H:i:s');
      if($message->type === 'server'){
        Gateway::bindUid($client_id, $message->from);
        if(!in_array($message->from, $online)){
          $online[] = $message->from;// 将上线的客服归到一组
        }
        var_dump($online);
        //GateWay::sendToAll($online);
        if($message->to){
          Gateway::sendToClient($message->to, json_encode($message));        
        }
      }else if($message->type === 'user'){
        $message->from = $client_id;
        Gateway::joinGroup($client_id, $message->to); // 将同一个客服的用户归到一组
        //var_export(Gateway::getClientSessionsByGroup($message->to));
        $message->userlist = Gateway::getClientSessionsByGroup($message->to);
        Gateway::sendToUid($message->to, json_encode($message));
      }
    }else{// 用户刚接入，返回在线客服数组
      $msg->type = 'online';
      $msg->online = $online;
      Gateway::sendToClient($client_id, json_encode($msg)); 
      echo "linked";
    }
    var_dump($message);
  }

  /**
  * 当用户断开连接时触发
  * $client_id 连接id
  */
  public static function onClose($client_id){
    $message->type = 'logout';
    $message->msg = '';
    $message->to = '';
    $message->from = $client_id;
    $message->time = '';
    $message->userlist = array();
    // 向所有人发送
    GateWay::sendToAll(json_encode($message));
  }
}
