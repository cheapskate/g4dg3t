<?php
set_time_limit(0);
ini_set('display_errors', 'on');
date_default_timezone_set("America/New_York");
$config = array( 
        'server' => 'irc.lightning.net', 
        'port'   => 6667, 
        'channel' => '#g4dg3t',
        'name'   => '5ecret', 
        'nick'   => 'flipaa', 
        'nick2'   => 'flipa1a', 
        'pass'   => '', 
);

class IRCBot {
	
//=========================================================================================================================================	

        var $socket;
		
        var $ex = array();
		
        function __construct($config)
        {
                $this->socket = fsockopen($config['server'], $config['port']);
                $this->login($config);
                $this->main($config);
        }
		
        function login($config)
        {
                $this->send_data('USER', $config['nick'].' 5ecret '.$config['nick'].' :'.$config['name']);
                $this->send_data('NICK', $config['nick']);
				$this->join_channel($config['channel']);
        }
		
		function log($data)
		{
				
			$servername = "localhost";
			$username = "root";
			$password = "devev4l";
			$dbname = "ircbot";
			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 
			$sql = "INSERT INTO ChatLog (date, time, text)
			VALUES ('".date("Y/m/d")."', '".date("h:i:sa")."', '".$data."')";
			
			if ($conn->query($sql) === TRUE) {
				echo "New record created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			$conn->close();
		}
		function chat($data)
		{
			$servername = "localhost";
			$username = "root";
			$password = "devev4l";
			$dbname = "ircbot";
			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 
			$sql = "INSERT INTO Chat (date, time, text)
			VALUES ('".date("Y/m/d")."', '".date("h:i:sa")."', '".$data."')";
			
			if ($conn->query($sql) === TRUE) {
				echo "New record created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			$conn->close();
		}
//=========================================================================================================================================		

        function main($config)
        {
			$data = fgets($this->socket, 256);
			
			
			
			
			
			flush();
			$this->ex = explode(' ', $data);
			
			
			if(isset($this->ex[0]) && $this->ex[0] == 'PING')
			{
					$this->send_dataQ('PONG', $this->ex[1]);
			}
			elseif (!empty($data))
			{
				$this->log($data);
			}
			
			
			if(isset($this->ex[1]))
			{
				
				
				if ($this->ex[1] == '433')
				{
					$this->send_data('NICK', $config['nick2']);
					$this->join_channel($config['channel']);
				}
				
				
				if ($this->ex[1] == 'QUIT')
				{
					$nickk = $this->parseID($this->ex[0]);
					if (strtolower($nickk['nick']) == strtolower($config['nick']))
					{
						$this->send_data('NICK', $config['nick']);
					}
					$this->chat('<b>'.$nickk['nick'].'</b> quit.');
				}
				
				if ($this->ex[1] == 'NICK')
				{
					$nickk = $this->parseNICK($data);
					if (strtolower($nickk['old']) == strtolower($config['nick']))
					{
						$this->send_data('NICK', $config['nick']);
					}
					$this->chat('<b>'.$nickk['old'].'</b> change to <b>' . $nickk['new'] . '</b>.');
				}
				
				
				if ($this->ex[1] == 'JOIN')
				{
					$join = $this->parseJOIN($data);
					if (strpos($this->ex[0],'5ecret@c-24-147-27-207.hsd1.ct.comcast.net') !== false)
					{
						$this->send_data('MODE '.$join['chan'].' +o ' . $join['nick']);
						$this->send_data("NOTICE",$join['nick'].' Hello.');
					}
					$this->chat('<b>'.$join['nick'].'</b> joined <b>'.$join['chan'] . '</b>.');
					
				}
				
				if ($this->ex[1] == 'PART')
				{
					$part = $this->parsePART($data);
					$this->chat('<b>'.$part['nick'].'</b> left <b>'.$part['chan'] . '</b>.');
				}
				
				if ($this->ex[1] == 'MODE')
				{
					$mode = $this->parseMODE($data);
					$this->chat('<b>'.$mode['nick'].'</b>('.$mode['chan'].') <b>'.$mode['mode'] . ' ' . $mode['user'] . '</b>.');
				}
				
				if ($this->ex[1] == 'NOTICE') {
					if (isset($this->ex[3])) {
						$nID = $this->parseNOTICE($data);
						$this->chat('<b>'.$nID['nick'] . '</b>(' . $nID['chan'] . '):NOTICE: <b>' . $nID['msg'].'</b>');
					}
				}	
				
				if ($this->ex[1] == 'PRIVMSG') {
					
					if (isset($this->ex[3])) {
						
						
						if (strpos($this->ex[0],'5ecret@c-24-147-27-207.hsd1.ct.comcast.net') !== false)
						{
							$command = str_replace(array(chr(10), chr(13)), '', $this->ex[3]);
							switch($command)
							{						 
								case ':!r':
									if (isset($this->ex[4])) { 
										$message = "";
										for($i=4; $i <= (count($this->ex)); $i++)
										{
											if (isset($this->ex[$i])) {
												$message .= $this->ex[$i]." ";
											}
										}
										$this->send_data($message);
									}
									break;
										
								case ':!restart':
									echo "<meta http-equiv=\"refresh\" content=\"5\">";
									exit;
										
								case ':!sd':
									$this->send_data('QUIT', '');
									exit;
										
								default:
									$nID = $this->parsePRIVMSG($data);
									$this->chat('<b>'.$nID['nick'] . '</b>(' . $nID['chan'] . '): <b>' . $nID['msg'].'</b>');
									break;
											
							} //End Switch
						}
						else
						{
							$nID = $this->parsePRIVMSG($data);
							$this->chat('<b>'.$nID['nick'] . '</b>(' . $nID['chan'] . '): <b>' . $nID['msg'].'</b>');
						}
					}
					
				}
				
				
			}
			//$this->readPOST();
			$this->main($config);
        }
		
//=========================================================================================================================================			
		
        function send_data($cmd, $msg = null)
        {
                if($msg == null)
                {
                        fputs($this->socket, $cmd."\r\n");
						$this->log($cmd);
                } else {

                        fputs($this->socket, $cmd.' '.$msg."\r\n");
						$this->log($cmd.' '.$msg);
                }

        }
        function send_dataQ($cmd, $msg = null)
        {
                if($msg == null)
                {
                        fputs($this->socket, $cmd."\r\n");
                } else {

                        fputs($this->socket, $cmd.' '.$msg."\r\n");
                }

        }
        function join_channel($channel)
        {
                if(is_array($channel))
                {
                        foreach($channel as $chan)
                        {
                                $this->send_data('JOIN', $chan);
                        }

                } else {
                        $this->send_data('JOIN', $channel);
                }
        }
		function parseID($id)
		{
			$SplitID['nick'] = substr($id,1,strpos($id,'!') - 1);
			$SplitID['ident'] = substr($id, strpos($id,'!') + 1, strpos($id,'@') - strpos($id,'!') - 1);
			$SplitID['host'] = substr($id, strpos($id,'@') + 1, strlen($id) - strpos($id,'@') - 1);
			return $SplitID;
		}
		function parseJOIN($raw)
		{
				$exx = explode(' ', $raw);
				$nID = $this->parseID($exx[0]);
				$nID['chan'] = str_replace(array(chr(10), chr(13), ':'), '', $exx[2]);
				return $nID;
		}
		function parsePART($raw)
		{
				$exx = explode(' ', $raw);
				$nID = $this->parseID($exx[0]);
				$nID['chan'] = str_replace(array(chr(10), chr(13), ':'), '', $exx[2]);
				return $nID;
		}
		function parseMODE($raw)
		{
				$exx = explode(' ', $raw);
				$nID = $this->parseID($exx[0]);
				$nID['chan'] = str_replace(array(chr(10), chr(13), ':'), '', $exx[2]);
				$nID['mode'] = str_replace(array(chr(10), chr(13), ':'), '', $exx[3]);
				$nID['user'] = "";
				if (isset($exx[4])) {
					for($i=4; $i <= (count($exx)); $i++)
					{
						if (isset($exx[$i])) {
							$nID['user'] .= $exx[$i]." ";
						}
					}
				}
				return $nID;
		}
		function parsePRIVMSG($raw)
		{
				$exx = explode(' ', $raw);
				$nID = $this->parseID($exx[0]);
				$nID['chan'] = $exx[2];
				$nID['msg'] = "";
				for($i=3; $i <= (count($exx)); $i++)
				{
					if (isset($exx[$i])) {
						$nID['msg'] .= $exx[$i]." ";
					}
				}
				$nID['msg'] = substr($nID['msg'],1,strlen($nID['msg']) - 1);
				return $nID; 	
		}
		function parseNOTICE($raw)
		{
				$exx = explode(' ', $raw);
				$nID = $this->parseID($exx[0]);
				$nID['chan'] = $exx[2];
				$nID['msg'] = "";
				for($i=3; $i <= (count($exx)); $i++)
				{
					if (isset($exx[$i])) {
						$nID['msg'] .= $exx[$i]." ";
					}
				}
				$nID['msg'] = substr($nID['msg'],1,strlen($nID['msg']) - 1);
				return $nID; 	
		}
		function parseNICK($raw)
		{
				$exx = explode(' ', $raw);
				$nID = $this->parseID($exx[0]);
				$nID['old'] = $nID['nick'];
				
				$nID['new'] = substr($exx[2],1,strlen($exx[2]) - 1);
				return $nID; 	
		}
		function readPOST()
		{
			$file = "post.txt"; 
				$handle = fopen($file,"r");
				$data = fread($handle, filesize($file));
				if (!empty($data)) {
					$exx = explode(' ',$data);
					if (isset($exx[0]) && isset($exx[1])) {
						$sendstring = 'PRIVMSG '.$exx[0].' <'.$exx[1].'>';
						if (isset($exx[2])) {
							for($i=2; $i <= count($exx); $i++)
							{
								if (isset($exx[$i])) {
									$sendstring = $sendstring . " " . $exx[$i];
								}							
							}
							$this->send_data($sendstring);
						}
					}
				}
				fclose($handle);				
		
				$handle = fopen($file,"w");
				fwrite($handle,"");
				fclose($handle);
		}
}

//=========================================================================================================================================	

$bot = new IRCBot($config);
?>