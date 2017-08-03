<!DOCTYPE html>
<html>
<head>
    <title>客户端</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <style>
      body{
        background-color: lightblue;
      }
      .ask-me{
        width: 150px;
        margin: 0 auto;
        text-align: center;
      }
      #click-me,
      #begin-chat{
        background-color: white;
        border: 1px solid #333;
        padding: 10px 15px;
        cursor: pointer;
        outline: none;/*去掉点击后的边框*/
      }
      #click-me:hover{
        background-color: #44b549;
        border-color: #44b549;
        color: white;
      }
      .select{
        width: 100px;
        margin: 0 auto;
        display: none;
      }
      .chat-container{
        width: 520px;
        margin: 0 auto;
        margin-top: 20px;
        border: 1px solid #333;
        background-color: white;
        display: none;
      }
      #show-msg{
        width: 500px;
        height: 500px;
        margin: 10px;
        overflow-y: auto;
        overflow-x: hidden;
      }
      #show-msg::after{
        display: block;
        clear: both;
        height: 0;
        content: '';
      }
      #show-msg section{
        clear: both;
      }
      #show-msg p{
        background-color: lightblue;
        max-width: 420px;
        padding: 5px;
        border-radius: 5px;
        display: inline-block;
        position:relative;
        vertical-align: top;
        word-wrap:break-word
      }
      #show-msg p.left{
        margin-left: 10px;
      }
      #show-msg p.left::before,
      #show-msg p.right::before{
        content: '';
        width: 0;
        height: 0;
        border: 8px solid transparent;
        position: absolute;
      }
      #show-msg p.left::before{
        border-right-color: lightblue;
        transform: translateX(-20px);
      }
      #show-msg p.right{
        margin-right: 10px;
        float: right;
      }
      #show-msg p.right::before{
        border-left-color: lightblue;
        right: -15px;
      }

      /*输入框*/
      #chat-box{
        text-align: center;
        margin: 10px 0;
      }
      #msg{
        width: 80%;
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
      
      p.title{
        width: 100%;
        background-color: #eee;
        padding: 10px 0;
        text-align: center;
        margin: 0;
      }
      p.title span.close{
        float: right;
        color: red;
        margin-right: 10px;
        cursor: pointer;
      }
    </style>
</head>
<body>
<div class="ask-me">
  <button id="click-me">有问题点我</button>
</div>

<div class="chat-container">
  <p class="title">客服
    <span id="s_num"></span>
    <span class="close">&#10006</span>
  </p>

  <!--聊天框-->
  <div id="show-msg"></div>
  
  <form id="chat-box">
      <input id="msg" type="text" name="message" />
      <input id="sm" type="submit" name="submit" value="提交" />
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="index.js"></script>
</body>
</html>