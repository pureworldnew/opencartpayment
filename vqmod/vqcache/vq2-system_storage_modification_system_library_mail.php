<?php
require_once(\VQMod::modCheck(DIR_SYSTEM . 'library/phpmailer/PHPMailerAutoload.php'));
class Mail {
	protected $to;
	protected $from;
	protected $sender;
	protected $reply_to;

protected $bcc;

protected $cc;

protected $emailtemplate;
	protected $subject;
	protected $text;
	protected $html;
	protected $attachments = array();
	public $protocol = 'mail';
	public $smtp_hostname;
	public $smtp_username;
	public $smtp_password;
	public $smtp_port = 25;
	public $smtp_timeout = 5;
	public $newline = "\n";
	public $verp = false;
	public $parameter = '';

	public function __construct($config = array()) {
		foreach ($config as $key => $value) {
			$this->$key = $value;
		}
	}

	public function setTo($to) {
		$this->to = $to;
	}

	public function setFrom($from) {
		$this->from = $from;
	}

	public function setSender($sender) {
		$this->sender = $sender;
	}

	public function setReplyTo($reply_to) {
		$this->reply_to = $reply_to;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function setHtml($html) {
		$this->html = $html;
	}

	public function addAttachment($filename) {
		$this->attachments[] = $filename;
	}


public function getSubject() {
		return $this->subject;
	}

	public function setCc($address) {
		$this->cc = $address;
	}

	public function setBcc($address) {
		$this->bcc = $address;
	}

	public function setEmailTemplate(EmailTemplate $oEmail) {
		$this->emailtemplate = $oEmail;
	}
	

	public function getMailProperties() {
		return array(
			'to' => $this->to,
			'from' => $this->from,
			'sender' => $this->sender,
			'reply_to' => (property_exists($this, 'reply_to') ? $this->reply_to : $this->replyto),
			'cc' => $this->cc,
			'bcc' => $this->bcc,
			'subject' => $this->subject,
			'text' => $this->text,
			'html' => $this->html,
			'attachments' => $this->attachments
		);
	}
	
	
  
  public function addCC($mail) {
    $this->cc[] = $mail;
  }
  
  public function addBCC($mail) {
    $this->bcc[] = $mail;
  }
  
  public function send() {
		if (!$this->to) {
			trigger_error('Error: E-Mail to required!');
			exit();
		}

		if (!$this->from) {
			trigger_error('Error: E-Mail from required!');
			exit();
		}

		if (!$this->sender) {
			trigger_error('Error: E-Mail sender required!');
			exit();
		}

		if (!$this->subject) {
			trigger_error('Error: E-Mail subject required!');
			exit();
		}

		if ((!$this->text) && (!$this->html)) {
			trigger_error('Error: E-Mail message required!');
			exit();
		}
    
    $reply_to = $this->sender;
    
		if (!empty($this->reply_to)) {
			$reply_to = $this->reply_to;
		} else if (!empty($this->replyto)) {
			$reply_to = $this->replyto;
		}
    
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    
    $mail->SetFrom($this->from, $this->sender);
		$mail->AddReplyTo($reply_to);
    
    foreach ((array) $this->to as $to) {
      $mail->AddAddress($to);
    }
    
    foreach ((array) $this->cc as $cc) {
      $mail->AddCC($cc);
    }
    
    foreach ((array) $this->bcc as $bcc) {
      $mail->AddBCC($bcc);
    }

    $mail->isHTML($this->html);
    
    $mail->Subject = $this->subject;
    if ($this->html) {
      $mail->MsgHTML($this->html);
      if ($this->text) {
        $mail->AltBody = $this->text;
      }
    } else {
      $mail->Body = $this->text;
    }

		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
        $mail->addAttachment($attachment);
			}
		}

    
    if ($this->protocol == 'smtp') {
			$mail->IsSMTP();
      if (substr(VERSION, 0, 1) == 2) {
        $mail->Host = str_replace(array('tls://', 'ssl://'), '', $this->smtp_hostname);
        if($this->smtp_port == '587' || (substr($this->smtp_hostname, 0, 3) == 'tls')) {
          $mail->SMTPSecure = 'tls';
        } elseif($this->smtp_port == '465' || (substr($this->smtp_hostname, 0, 3) == 'ssl')) {
          $mail->SMTPSecure = 'ssl';
        }
        $mail->Port = $this->smtp_port;
        if (!empty($this->smtp_username)) {
          $mail->SMTPAuth = true;
          $mail->Username = $this->smtp_username;
          $mail->Password = $this->smtp_password;
        }
      } else {
        $mail->Host = str_replace(array('tls://', 'ssl://'), '', $this->hostname);
        if($this->port == '587' || (substr($this->hostname, 0, 3) == 'tls')) {
          $mail->SMTPSecure = 'tls';
        } elseif($this->port == '465' || (substr($this->hostname, 0, 3) == 'ssl')) {
          $mail->SMTPSecure = 'ssl';
        }
        $mail->Port = $this->port;
        if (!empty($this->username)) {
          $mail->SMTPAuth = true;
          $mail->Username = $this->username;
          $mail->Password = $this->password;
        }
      }
		}
    
		$mail->Send();
	}
  
  function send_default() {
      
		$this->_send();

		if ($this->emailtemplate) {
			$data = get_object_vars($this);

			$this->emailtemplate->sent($data);
		}
	}

	private function _send() {
		if (!$this->to) {
			trigger_error('Error: E-Mail to required!');
			exit();
		}

		if (!$this->from) {
			trigger_error('Error: E-Mail from required!');
			exit();
		}

		if (!$this->sender) {
			trigger_error('Error: E-Mail sender required!');
			exit();
		}

		if (!$this->subject) {
			trigger_error('Error: E-Mail subject required!');
			exit();
		}

		if ((!$this->text) && (!$this->html)) {
			trigger_error('Error: E-Mail message required!');
			exit();
		}

		if (is_array($this->to)) {
			$to = implode(',', $this->to);
		} else {
			$to = $this->to;
		}

		$boundary = '----=_NextPart_' . md5(time());

		$header = 'MIME-Version: 1.0' . $this->newline;

		if ($this->protocol != 'mail') {
			$header .= 'To: ' . $to . $this->newline;
			$header .= 'Subject: =?UTF-8?B?' . base64_encode($this->subject) . '?=' . $this->newline;
		}

		$header .= 'Date: ' . date('D, d M Y H:i:s O') . $this->newline;
		$header .= 'From: =?UTF-8?B?' . base64_encode($this->sender) . '?=' . ' <' . $this->from . '>' . $this->newline;
		
		if (!$this->reply_to) {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->sender) . '?=' . ' <' . $this->from . '>' . $this->newline;
		} else {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->reply_to) . '?=' . ' <' . $this->reply_to . '>' . $this->newline;
		}
		
		$header .= 'Return-Path: ' . $this->from . $this->newline;

		if ($this->bcc) {
			$header .= 'BCC: ' . $this->bcc . $this->newline;
		}
		

		if ($this->cc) {
			$header .= 'CC: ' . $this->cc . $this->newline;
		}
		
		$header .= 'X-Mailer: PHP/' . phpversion() . $this->newline;
		$header .= 'Content-Type: multipart/related; boundary="' . $boundary . '"' . $this->newline . $this->newline;

		if (!$this->html) {
			$message  = '--' . $boundary . $this->newline;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;
			$message .= $this->text . $this->newline;
		} else {
			$message  = '--' . $boundary . $this->newline;
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . $this->newline . $this->newline;
			$message .= '--' . $boundary . '_alt' . $this->newline;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;

			if ($this->text) {
				$message .= $this->text . $this->newline;
			} else {
				$message .= 'This is a HTML email and your email client software does not support HTML email!' . $this->newline;
			}

			$message .= '--' . $boundary . '_alt' . $this->newline;
			$message .= 'Content-Type: text/html; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;
			$message .= $this->html . $this->newline;
			$message .= '--' . $boundary . '_alt--' . $this->newline;
		}

		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
				$handle = fopen($attachment, 'r');

				$content = fread($handle, filesize($attachment));

				fclose($handle);

				$message .= '--' . $boundary . $this->newline;
				$message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . $this->newline;
				$message .= 'Content-Transfer-Encoding: base64' . $this->newline;
				$message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . $this->newline;
				$message .= 'Content-ID: <' . basename(urlencode($attachment)) . '>' . $this->newline;
				$message .= 'X-Attachment-Id: ' . basename(urlencode($attachment)) . $this->newline . $this->newline;
				$message .= chunk_split(base64_encode($content));
			}
		}

		$message .= '--' . $boundary . '--' . $this->newline;

		if ($this->protocol == 'mail') {
			ini_set('sendmail_from', $this->from);

			if ($this->parameter) {
				mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header, $this->parameter);
			} else {
				mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header);
			}
		} elseif ($this->protocol == 'smtp') {
			$tls = substr($this->smtp_hostname, 0, 3) == 'tls';
			$hostname = $tls ? substr($this->smtp_hostname, 6) : $this->smtp_hostname;

			$handle = fsockopen($hostname, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);

			if (!$handle) {
				trigger_error('Error: ' . $errstr . ' (' . $errno . ')');
				exit();
			} else {
				if (substr(PHP_OS, 0, 3) != 'WIN') {
					socket_set_timeout($handle, $this->smtp_timeout, 0);
				}

				while ($line = fgets($handle, 515)) {
					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . "\r\n");

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 250) {
					trigger_error('Error: EHLO not accepted from server!');
					exit();
				}

				if ($tls) {
					fputs($handle, 'STARTTLS' . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 220) {
						trigger_error('Error: STARTTLS not accepted from server!');
						exit();
					}

					stream_socket_enable_crypto($handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
				}

				if (!empty($this->smtp_username)  && !empty($this->smtp_password)) {
					fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 250) {
						trigger_error('Error: EHLO not accepted from server!');
						exit();
					}

					fputs($handle, 'AUTH LOGIN' . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 334) {
						trigger_error('Error: AUTH LOGIN not accepted from server!');
						exit();
					}

					fputs($handle, base64_encode($this->smtp_username) . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 334) {
						trigger_error('Error: Username not accepted from server!');
						exit();
					}

					fputs($handle, base64_encode($this->smtp_password) . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 235) {
						trigger_error('Error: Password not accepted from server!');
						exit();
					}
				} else {
					fputs($handle, 'HELO ' . getenv('SERVER_NAME') . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if (substr($reply, 0, 3) != 250) {
						trigger_error('Error: HELO not accepted from server!');
						exit();
					}
				}

				if ($this->verp) {
					fputs($handle, 'MAIL FROM: <' . $this->from . '>XVERP' . "\r\n");
				} else {
					fputs($handle, 'MAIL FROM: <' . $this->from . '>' . "\r\n");
				}

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 250) {
					trigger_error('Error: MAIL FROM not accepted from server!');
					exit();
				}

				if (!is_array($this->to)) {
					fputs($handle, 'RCPT TO: <' . $this->to . '>' . "\r\n");

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
						trigger_error('Error: RCPT TO not accepted from server!');
						exit();
					}
				} else {
					foreach ($this->to as $recipient) {
						fputs($handle, 'RCPT TO: <' . $recipient . '>' . "\r\n");

						$reply = '';

						while ($line = fgets($handle, 515)) {
							$reply .= $line;

							if (substr($line, 3, 1) == ' ') {
								break;
							}
						}

						if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
							trigger_error('Error: RCPT TO not accepted from server!');
							exit();
						}
					}
				}

				fputs($handle, 'DATA' . "\r\n");

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 354) {
					trigger_error('Error: DATA not accepted from server!');
					exit();
				}

				// According to rfc 821 we should not send more than 1000 including the CRLF
				$message = str_replace("\r\n", "\n", $header . $message);
				$message = str_replace("\r", "\n", $message);

				$lines = explode("\n", $message);

				foreach ($lines as $line) {
					$results = str_split($line, 998);

					foreach ($results as $result) {
						if (substr(PHP_OS, 0, 3) != 'WIN') {
							fputs($handle, $result . "\r\n");
						} else {
							fputs($handle, str_replace("\n", "\r\n", $result) . "\r\n");
						}
					}
				}

				fputs($handle, '.' . "\r\n");

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 250) {
					trigger_error('Error: DATA not accepted from server!');
					exit();
				}

				fputs($handle, 'QUIT' . "\r\n");

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 221) {
					trigger_error('Error: QUIT not accepted from server!');
					exit();
				}

				fclose($handle);
			}
		}
	}
}