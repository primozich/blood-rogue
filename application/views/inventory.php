<div id="workspace">
	<div class="redText" style="margin-top:0px;height:400px;width:745px;border:2px dashed #FF0000;">
		<div style="float:left;margin-left:60px;margin-top:40px;width:250px;">
			<div style="margin-bottom:5px;">
				<?php echo $ascii; ?>
			</div>
		</div>
		<div style="float:left;margin-left:20px;margin-top:40px;width:200px;">
	        <div style="font-size:18px;margin-bottom:20px;">You have read a scroll of Identify!</div>
	        <div style="margin-bottom:20px;">Click the item you wish to cast the spell on:</div>
	        <div>
	        <?php
	        	foreach ($inventory as $key => $item)
	        	{
	        		if ($key != $selectedIndex)
	        		{
	        ?>
	        	<a target="_top" href="http://<?php echo $env->www_root; ?>/rogue/identify/<?php echo $key; ?>" class="redText"><?php echo $item->getName(); ?></a><br />
	        <?php
	        		}
	        	}
	        ?>
	        </div>
		</div>
	</div>
</div>
<?php
if ($dimTheLights)
{
?>
<script type="text/javascript">
	hide_element = document.getElementById("workspace");
	hide_element.style.opacity = 0.10;
	hide_element.style.filter = "alpha(opacity=10)";
</script>
<?php
	print($popup);
}
?>