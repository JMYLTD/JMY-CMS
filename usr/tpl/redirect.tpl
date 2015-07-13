<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
		<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml"> 
			<head>
			    <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
				<title>Подождите.</title>
				<meta http-equiv="refresh" content="2; url=<?php echo $full_url; ?>" />
				<style type="text/css" media="all">
					body {
						background:#fff;
						font-size:12px;
						color:#999;
						font-family: Geneva, Arial, Helvetica, sans-serif;
					}

					a {
					color:#666;
					text-decoration:none;
					font-size:12px;
					}

					a:hover {
					color:#222;
					text-decoration:underline;
					}


					#redirectwrap{
						background: #FFF;
						border: 4px solid #eeeeee;
						padding: 1px;
						margin: 200px auto 0 auto;
						text-align: left;
						width: 400px;
						font-size:13px;
					}

					#redirectwrap h2{
					color:#111111;
					padding-left:5px;
					margin:0;
					margin-top:10px;
					margin-bottom:5px;
					}

					#redirectwrap p{
						margin: 0;
						padding: 5px;
					}

					#redirectwrap p.redirectfoot{
						margin: 0px !important;
						padding: 5px !important;
						text-align: center;
						border-top:1px #dedede dashed;
					}
				</style>
				<script type=\'text/javascript\'>
				//<![CDATA[
				// Fix Mozilla bug: 209020
				if ( navigator.product == \'Gecko\' )
				{
					navstring = navigator.userAgent.toLowerCase();
					geckonum  = navstring.replace( /.*gecko\/(\d+)/, "$1" );
					
					setTimeout("moz_redirect()",1500);
				}
				
				function moz_redirect()
				{
					var url_bit     = "<?php echo $full_url; ?>";
					window.location = url_bit.replace( new RegExp( "&amp;", "g" ) , \'&\' );
				}
				//>
				</script>
			</head>
			<body>
				<div id="redirectwrap">
					<h2><?php echo $text; ?></h2>
					<p><?php  echo $message; ?></p>
					<p class="redirectfoot">(<a href="<?php echo $full_url; ?>"><?php  echo _CLICK_IFWW; ?></a>)</p>
				</div>
			</body>
		</html>