<!DOCTYPE html>
<html>

<?php $this->load->view('templates/head'); ?>

<body>


<div class="container">
	<?php $this->load->view($header); ?>
</div>

<div class="container content-area">
	<?php $this->load->view($content); ?>
</div>

<div class="container">
	<?php $this->load->view('templates/footer'); ?>
</div>


<?= (function_exists('form_submit_scroll_script')) ? form_submit_scroll_script() : ''; ?>

</body>
</html>
