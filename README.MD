网页客服系统

当前目录是支持多个客服的，但是客服选择了自己是哪个客服之后需要随便发一个消息到后台绑定一下自己的号码，然后用户发的消息就能找到对应的客服了。

one-server里面是一个客服的，更简单，进入客服界面就自动发消息绑定了客服的代码

运行方法：cmd进入/Applications/Chat目录，输入php start_register.php start_web.php start_gateway.php start_businessworker.php即可启动服务，打开浏览器输入http://localhost:2347进入用户端，http://localhost:2347/server.php进入客服端

也可以直接运行根目录下的start_for_win.bat启动服务
