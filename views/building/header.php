
<div class="head">
	<div class="header">
		<a class="logo" href="<?php echo $root ?><?php echo $confid ? "?cnf=".$confid : ""; ?>">
			<img height="22" src="<?php echo $root ?>views/icons/group44.png" alt="Contacts logo" style="-webkit-filter: invert(100%); filter: invert(100%);">
		</a>
			<?php
			if( isset($_SESSION['username']) && isset($_SESSION['userid']) ) {
			?>
				<a class="btn" title="Account management" href='<?php echo $confid ? "?cnf=".$confid : ""; ?>&p=settings'><?php echo $_SESSION['username']; ?></a>
				<a class="btn" href='?<?php echo $confid ? "cnf=".$confid : ""; ?>&p=logout'>Logout</a>
			<?php
			}else{
			?>
				<a class="btn" href="?p=register">Register</a>  
				<a class="btn" href="?p=login">Login</a>
			<?php
			}
			?>
	</div>
</div>