<div id="workspace">
	<div style="margin-top:30px;margin-left:50px;">
	<div class="redText" style="margin-top:30px;height:400px;width:650px;border:2px dashed #FF0000;">
		<div id="popup_body" style="float:left;margin-left:30px;width:350px;">
			<div style="margin-bottom:5px;">
				<?php echo $ascii; ?>
			</div>
			<!-- div>
				<div style="margin-left:130px;float:left;padding:20px;">
					<a onclick="popup_continue();return false;" href="#" class="redText">CONTINUE</a>
				</div>
			</div-->
		</div>
		<div style="float:left;margin-left:20px;margin-top:40px;width:200px;">
	        <div style="font-size:18px;margin-bottom:20px;"><?php echo $message; ?></div>
	        <div><a target="_top" href="http://<?php echo $env->www_root; ?>/rogue/reset" class="redText">Click here to resurrect</a></div>
		</div>
	</div>
	</div>
	<div style="clear:both;height:20px;"></div>
</div>
