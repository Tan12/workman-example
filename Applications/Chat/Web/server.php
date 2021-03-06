<!DOCTYPE html>
<html>
<head>
  <title>客服端</title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8">
  <style>
    body{
      font-size: 18px;
    }
    .container{
      width: 1000px;
      margin: 0 auto;
      margin-top: 20px;
    }
    #user-list{
      margin-top: 30px;
    }
    .show-box{
      width: 100%;
      min-width: 600px;
      height: 480px;
      border: 1px solid #333;
    }
    .show-box div[id^='show-msg']{
      width: 980px;
      height: 460px;
      overflow-y: auto;
      overflow-x: hidden;
      position: absolute;
      margin: 10px;
      background-color: white;
    }
    .show-box section{
      clear: both;
    }
    .show-box p.left,
    .show-box p.right{
      background-color: lightblue;
      max-width: 600px;
      padding: 5px;
      border-radius: 5px;
      display: inline-block;
      position:relative;
      vertical-align: top;
      word-wrap:break-word
    }
    .show-box p.left{
      margin-left: 10px;
    }
    .show-box p.left::before,
    .show-box p.right::before{
      content: '';
      width: 0;
      height: 0;
      border: 8px solid transparent;
      position: absolute;
    }
    .show-box p.left::before{
      border-right-color: lightblue;
      transform: translateX(-20px);
    }
    .show-box p.right{
      margin-right: 10px;
      float: right;
    }
    .show-box p.right::before{
      border-left-color: lightblue;
      right: -15px;
    }
    .show-box p.center{ /*系统提示*/
      width: 100%;
      text-align: center;
      font-size: .8rem;
      background-color: #e6e6e6;
      padding: 5px;
    }
    #user-list{
      margin-top: 30px;
    }
    .current-user{
      z-index: 1024;
    }
    .hidden{
      display: none;
    }

    input[type=radio]{
      margin: 0;
    }
    label{
      cursor: pointer;
    }
    .tags{
      display: inline-block;
      border: 1px solid #333;
      border-bottom-color: transparent;
      color: #9c9c9c;
      padding: 10px 20px;
      border-radius: 10px 10px 0 0;
    }
    #user-list div:nth-of-type(n+2){
      margin-left: -5px;
      border-left: transparent;
    }

    /*当前用户标签样式*/
    .current{
      color: #44b549;
      font-weight: bold;
    }
    /*新消息提醒样式*/
    .new::before{
      content: '';
      width: 10px;
      height: 10px;
      border-radius: 100%;
      background-color: red;
      position: absolute;
      transform: translate(-12px,-5px);
      z-index: 999;
    }
    /*关闭按钮样式*/
    .close{
      color: red;
      display: inline-block;
      transform: translate(8px, -1px);
      cursor: pointer;
    }

    /*输入框*/
    #chat-box{
      margin-top: 30px;
      text-align: center;
    }
    #msg{
      width: 50%;
      border: 1px solid #44b549;
      padding: 8px 5px;
    }
    #sm{
      padding: 6px 20px;
      background-color: white;
      border: 1px solid #44b549;
      cursor: pointer;
    }
    #msg,
    #sm{
      border-radius: 5px;
    }
  </style>
</head>
<body>
<div class="container">
  <p>你是？</p>
  <select id="server-list">
    <option value="001">客服1</option>
    <option value="002">客服2</option>
  </select>

  <form id="user-list"></form>

  <div class="show-box"></div>

  <form id="chat-box">
      <input id="msg" type="text" name="message" />
      <input id="sm" type="submit" name="submit" value="提交" />
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="server.js"></script>
</body>
</html>