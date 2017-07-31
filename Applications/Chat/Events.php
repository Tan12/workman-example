<?php
use \GatewayWorker\Lib\Gateway;
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
  * @param int $client_id 连接id
  */
  public static function onConnect($client_id){
    // 向当前client_id发送数据
    Gateway::sendToClient($client_id, "Hello $client_id");
    // 向所有人发送
    //Gateway::sendToAll("$client_id login");
  }

  /**
  * 当客户端发来消息时触发
  * @param int $client_id 连接id
  * @param string $message 具体消息
  */
  public static function onMessage($client_id, $message){
    $message = json_decode($message);
    $message->time = date('Y-m-d H:i:s');
    // 将相同客服的用户id放到一个组里面
    $server_1 = [];
    $server_2 = [];
    if($message->type === 'server'){
      Gateway::bindUid($client_id, $message->from);
      if($message->to){
        Gateway::sendToClient($message->to, json_encode($message));        
      }
    }else if($message->type === 'user'){
      $message->from = $client_id;
      Gateway::joinGroup($client_id, $message->to);
      var_export(Gateway::getClientSessionsByGroup($message->to));
      $message->userlist = Gateway::getClientSessionsByGroup($message->to);
      Gateway::sendToUid($message->to, json_encode($message));
    }
    var_dump($message);
  }

  /**
  * 当用户断开连接时触发
  * @param int $client_id 连接id
  */
  public static function onClose($client_id){
       // 向所有人发送
       GateWay::sendToAll("$client_id logout");
  }
}
