<?php

	class Request {
		public function Request()
		{
			#$this->get_site_url();
			$this->get_identifier_domain();
			$this->get_virtual_path();
		}
		
		
		
		public function get_virtual_path()
		{
			if (!isset($this->virtual_path))
			{
				$start=(strlen($this->path)-1);
				if ($start<=0) { $start=0; }
				$temp=substr($_SERVER["REQUEST_URI"],$start);
				
				if (!empty($_SERVER["QUERY_STRING"]))
				{
					$temp=substr($temp,0,strlen($temp)-(strlen($_SERVER["QUERY_STRING"])+1) );
				}
				
				$this->virtual_path=$temp;
				if (substr($this->virtual_path,strlen($this->virtual_path)-1)!="/")
				{
					$qry="";
					if (!empty($_SERVER["QUERY_STRING"])) { $qry="?".$_SERVER["QUERY_STRING"]; }
					$this->redirect($this->get_site_url().substr($this->virtual_path,1)."/".$qry,301);
				}
			}
			return $this->virtual_path;
		}
		
		
		public function redirect ($url="",$mode=200)
		{
			if ($mode==200) { header("HTTP/1.1 ".$mode." OK"); }
			if ($mode==301) { header("HTTP/1.1 ".$mode." Moved Permanently"); }
			
			if (empty($url))
			{
				$url=$this->get_site_url();
			}
			
			header("Location: ".$url);
			
			exit;
		}
		
		########### GET ##################
		public function get_site_url()
		{
			if (!isset($this->site_url))
			{
				$this->site_url=$this->get_protocol()."://".$this->get_domain().$this->get_port_string().$this->get_path();
			}
			return $this->site_url;
		}
		
		public function get_identifier_domain()
		{
			if (!isset($this->identifier_domain))
			{
				$this->get_site_url();
				$id=$this->get_domain().$this->get_port_string().$this->get_path();
				$this->identifier_domain=$id;				
			}
			return $this->identifier_domain;
		}
		
		public function get_protocol()
		{
			if (!isset($this->protocol))
			{
				$prot="http";
				if (isset($_SERVER["HTTPS"]))
				{
					if ($_SERVER["HTTPS"])
					{
						$prot.="s";
					}
				}
				$this->protocol=$prot;
			}
			return $this->protocol;
		}
		
		public function get_domain()
		{
			if (!isset($this->domain))
			{
				$this->domain=$_SERVER["SERVER_NAME"];
			}
			return $this->domain;
		}
		
		public function get_path()
		{
			global $_EF;
			if (!isset($this->path))
			{
				$path=str_replace($_SERVER["DOCUMENT_ROOT"],"",$_EF->initial_directory);
				$this->path=$path;
			}
			return $this->path;
		}
		
		public function get_port()
		{
			if (!isset($this->port))
			{
				$this->port=$_SERVER["SERVER_PORT"];
				if ($this->port!=443 AND $this->port!=80)
				{
					$this->port_string=":".$this->port;
				}
				else
				{
					$this->port_string="";
				}
			}
			return $this->port;
		}
		public function get_port_string()
		{
			$this->get_port();
			return $this->port_string;
		}
		
	}

?>
