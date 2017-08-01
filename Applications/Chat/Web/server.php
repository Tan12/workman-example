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
      width: 60%;
      margin: 0 auto;
      margin-top: 20px;
    }
    .show-box{
      width: 100%;
      min-width: 600px;
      height: 480px;
      border: 1px solid #333;
    }
    .show-box .username{
      background-color: white;
      text-align: center;
    }
    .show-box div[id^='show-msg']{
      width: 58%;
      height: 460px;
      overflow-y: auto;
      position: absolute;
      margin: 10px;
      border: 1px solid red;
      background-color: white;
    }
    .show-box p{
      background-color: lightblue;

    }
    #user-list{
      margin-top: 30px;
      padding: 10px 0;
    }
    #chat-box{
      margin-top: 30px;
      text-align: center;
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
      border: 1px solid #333;
      color: #9c9c9c;
      padding: 10px 20px;
      border-radius: 10px 10px 0 0;
      cursor: pointer;
    }
    label:nth-of-type(n+2){
      margin-left: -5px;
      border-left: transparent;
    }
    .current{
      color: #44b549;
      font-weight: bold;
    }

    /*输入框*/
    #msg{
      width: 50%;
      border: 1px solid #44b549;
      padding: 8px 5px;
    }
    #sm{
      padding: 6px 20px;
      background-color: white;
      border: 1px solid #44b549;
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

  <form id="user-list">
    <input type="radio" id="user10" class="hidden" value="7f00000108fc0000000c" name="user" checked="checked">
    <label class="current" for="user10">用户10</label>
    <input type="radio" id="user20" class="hidden" value="7f00000108fc0000000c" name="user" checked="checked">
    <label for="user20">用户20</label>
    <input type="radio" id="user20" class="hidden" value="7f00000108fc0000000c" name="user" checked="checked">
    <label for="user20">用户20</label>
  </form>

  <div class="show-box">
    <p>当前用户：用户<span id="usernum">1</span></p>
  </div>
  <form id="chat-box">
      <input id="msg" type="text" name="message" />
      <input id="sm" type="submit" name="submit" value="提交" />
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="server.js"></script>
</body>
</html>