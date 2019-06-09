<!DOCTYPE html>
<html>

<?php $this->load->view('templates/head'); ?>

<body>


<div class="container">
	<?php $this->load->view('templates/header_login'); ?>
</div>

<div class="container">
	<div class="content-area">
		<?php $this->load->view($content); ?>
	</div>
</div>


</body>
</html>
