<div style="font-family:monospace">
	<p>The following error occurred on <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>"><?php echo $_SERVER['HTTP_HOST']; ?></a> @ <?php echo date('r'); ?>:</p>
	<p><strong><?php echo $error['message']; ?></strong></p>
	<p><em>...in the file:</em></p>
	<p><strong><?php echo $error['file'].' ('.$error['line'].')'; ?></strong></p>
	<p><em>...while trying to process the request:</em></p>
	<p><strong><a href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>"><?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?></a></strong></p>
	<p><em>Trace:</em></p>
	<pre><?php echo $error['trace']; ?></pre>
</div>
