<?php include "scripts/fsbb/fsbb.php"; ?><?php $rvblockerOF= new formSpamBotBlocker();$rvblockerOF->setTimeWindow(2,14400);$rvblockerOF->setTrap(true,"spambot"); ?><?php  $makeTags = $rvblockerOF->makeTags(); ?><?php 
                            /**
                            * validateManageComponent($input = component name)
                            */                           
                             if ( is_file( dirname(__FILE__) . "/scripts/rvslib/component/rvsManageComponent.php") ) {
                                include( dirname(__FILE__) . "/scripts/rvslib/component/rvsManageComponent.php");
                                rvsManageComponent::validateManageComponent('Online_Form');
                            } elseif ( is_file( dirname(dirname(__FILE__)) . "/.rvsitebuilder/rvsManageComponent.php") ) {
                                include( dirname(dirname(__FILE__)) . "/.rvsitebuilder/rvsManageComponent.php");
                                rvsManageComponent::validateManageComponent('Online_Form');
                            }
                        ?><?php echo '<?xml version="1.0" encoding="iso-8859-15" ?>'; ?>
echo "made it here";
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Resolute Academy - NO TAKS to graduate</title>

<meta http-equiv="Content-Type" content="text/html; charset =iso-8859-15" />
<meta name="keywords" content="TAKS,graduate,diploma,GED,state testing,dropout,resolute, online private high school" />
<meta name="description" content="Resolute Academy can help you earn an online high school diploma even if you cannot pass TAKS" />
 
<link rel="stylesheet" href="http://resoluteacademy.com/style.css" type="text/css" />
<link rel="stylesheet" href="http://resoluteacademy.com/Verdana.css" type="text/css" />
<link rel="stylesheet" href="http://resoluteacademy.com/f70e54984f0efdc51980b27fddfe4266.css" type="text/css" />


<script language="JavaScript" type="text/javascript" src="http://resoluteacademy.com/rvsincludefile/rvsnavigator.js"></script>


<script language="JavaScript" type="text/javascript" src="http://resoluteacademy.com/js/layersmenu-library.js"></script>
<script language="JavaScript" type="text/javascript" src="http://resoluteacademy.com/js/layersmenu.js"></script>

<script language="JavaScript" type="text/javascript" src="http://resoluteacademy.com/rvsincludefile/rvscustomopenwindow.js"></script>


</head>

<body>
<table cellpadding="0" cellspacing="0" id="rv_top_adjust_width_0" width="780" align="Center" >
  <tr>
    <td align="left" valign="top">
		<!-- START LOGO -->
			<div style="position: absolute;">
				<div id="Layer1" style="position:relative; left:20px; top:15px; width:120; height:60; text-align:center; z-index:1; overflow:visible; white-space:nowrap;"></div>
			</div>
			<div style="position: absolute;">
				<div id="Layer2" style="position:relative; left:190px; top:20px; width:auto; height:auto; text-align:left; z-index:2; overflow:visible; white-space:nowrap;" class="company"></div>
			</div>
			<div style="position: absolute;">
				<div id="Layer3" style="position:relative; left:190px; top:70px; width:auto; height:auto; text-align:left; z-index:3; overflow:visible; white-space:nowrap;" class="slogan"></div>
			</div>
		<!-- END LOGO -->
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="bgheader" align="center"><img src="images/resoluteheader.jpg"  /></td>
			</tr>
			<tr><td class="colorline01"></td></tr>
			<tr><td class="colorline02"><img src="images/spacer.gif" width="1" height="6" /></td></tr>
			<tr>
				<td align="left" valign="top">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="bgnavigator" align="left" valign="top" height="32"><div id="navigator">
<ul id="navigator">
<li><a href="index.php"   id="normal"  target="_self" >Home</a></li>
<li><a href="About-us.php"   id="normal"  target="_self" >About us</a></li>
<li><a href="Contact-Us.php"   id="normal"  target="_self" >Contact Us</a></li>
<li><a href="FAQ.php"   id="normal"  target="_self" >FAQ</a></li>
</ul>
</div>

</td>
						</tr>
						<tr><td class="bgtshadow"><img src="images/spacer.gif" alt="" width="1" height="12" /></td></tr>
						<tr>
							<td align="left" valign="top">
								<table cellpadding="0" cellspacing="0" width="100%" class="bgbody">
									 

									<!-- Begin PATHWAY and ICON -->
									<tr>
										<td class="magin">
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<!-- Begin PATHWAY -->
													<td align="left" width="99%"><?php include('/home/resacad/public_html/rvsincludefile/pathway_Contact-Us.html'); ?></td>
													<!-- End PATHWAY -->
													<!-- Begin ICON -->
													<td align="right"><?php include('/home/resacad/public_html/rvsincludefile/icon_Contact-Us.html'); ?></td>
													<!-- End ICON -->
												</tr>
											</table>								
										</td>
									</tr>
									<!-- End PATHWAY and ICON -->
									<tr>
										<td align="left" valign="top">
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													 

													<td align="left" valign="top" class="magin" id="rv_adjust_width_0" width="780">

<script language="JavaScript" type="text/javascript" src="scripts/form/CheckValidate.js"></script><div align="center"><form action="scripts/form/rvform.php" method="post" name="formIdca9a62ba400d19072dae07681b6a674f" id = "formIdca9a62ba400d19072dae07681b6a674f" onsubmit="return  CheckValidate('ca9a62ba400d19072dae07681b6a674f' , '6','Name','Email','Phone','Subject','MyFace','Comment','Your Name','Email','Phone','Subject','MySpace/Facebook','Comment','NotValid','Email','NotValid','NotValid','NotValid','NotValid')" ><?php  print $makeTags; ?><input type="hidden" name="redirect" value="../../rvthankform_ca9a62ba400d19072dae07681b6a674f.php" /><input type="hidden" name="missing_field_redirect" value="../../rvthankform_ca9a62ba400d19072dae07681b6a674f.php" /><input type="hidden" name="rvformid" value="ca9a62ba400d19072dae07681b6a674f" /><input type="hidden" name="charset" value="iso-8859-15" /><table width="95%" bgcolor="#f0f0f0" align="left" cellspacing="1" cellpadding="3"  style="color:#000000;color:#000000;text-align:left;border:3px solid #ffffff;"|#c6c6c6|#c6c6c6  id="onlineFormTable"><tr valign="top" bgcolor="#c6c6c6"><td width="100">Your Name**:</td>
                <td class="textinside"><input name="Name" id="Name" type="text" value="" size="30" maxlength="30" ></td>
        </tr><tr valign="top" bgcolor="#c6c6c6"><td width="100">Email**:</td>
                <td class="textinside"><input name="Email" id="Email" type="text" value="" size="30" maxlength="50" ></td>
        </tr><tr valign="top" bgcolor="#c6c6c6"><td width="100">Phone:</td>
                <td class="textinside"><input name="Phone" id="Phone" type="text" value="" size="20" maxlength="20" ></td>
        </tr><tr valign="top" bgcolor="#c6c6c6"><td width="100">Subject:</td>
                <td class="textinside"><input name="Subject" id="Subject" type="text" value="More Info..." size="50" maxlength="50" ></td>
        </tr><tr valign="top" bgcolor="#c6c6c6"><td width="100">MySpace/Facebook:</td>
                <td class="textinside"><input name="MyFace" id="MyFace" type="text" value="" size="50" maxlength="50" ></td>
        </tr><tr valign="top" bgcolor="#c6c6c6"><td width="100">Comment:</td>
                <td class="textinside"><textarea name="Comment" id="Comment" cols="30" rows="6" wrap="Default" id="Comment"></textarea></td>
            </tr><tr valign="top" bgcolor="#c6c6c6"><td width="100"></td><input type="hidden" name="validated" value="Email?Email"><input type="hidden" name="required" value="Name,Email"><td><input type="submit" name="submit" value="Submit">&nbsp;<input type="reset" name="reset" value="Reset"></td></tr><tr align="center" rvComm="reqField" ><td colspan="2" nowrap > (** Required Fields) </ td></tr></table></form></div><br />
<br /><br />
<br />
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
</td>
													 
				
												</tr>
											</table>								
										</td>
									</tr>
									<!-- Begin FOOTER -->
									<tr>
										<td align="center" class="magin"></td>
									</tr>
									<!-- End FOOTER -->
									<!-- Begin BOTTOM -->
									<tr>
										<td align="center" class="magin">
<div align="center"><font color="#000000">Copyright 2009 Resolute Academy</font><br />
</div></td>
									</tr>
									<!-- End BOTTOM -->
									<tr>
										<td align="center" valign="bottom">
											<table cellpadding="0" cellspacing="0">
												<tr>
													<td align="center" valign="bottom" class="marginpw"></td>
													<td width="8"></td>
													<td align="center" valign="bottom" class="marginpw"></td>
												</tr>
											</table>								
										</td>
									</tr>
								</table>		
							</td>
						</tr>
						<tr>
							<td class="bgbshadow"><img src="images/spacer.gif" alt="" width="1" height="18" /></td>
						</tr>
						<tr>
							<td class="bgfooter"><img src="images/spacer.gif" alt="" width="1" height="30" /></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
  </tr>
</table>
</body>
</html>

<!-- Publish : RVSiteBuilder PRO Version 3.22 -->
<!-- Name : resolute -->
<!-- Template : 17-617759-1_blue_DiyPicture_2 -->
<!-- ID : 9a2a5ad1d604bbde7f1ac39c6f522f3b -->