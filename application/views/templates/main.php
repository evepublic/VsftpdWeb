<!DOCTYPE html>
<html>

<?php $this->load->view('templates/head'); ?>

<body>


<div class="container">
	<?php $this->load->view('templates/header'); ?>
</div>

<div class="container">
	<div class="content-area">
		<?php $this->load->view($content); ?>
	</div>
</div>

<div class="container">
	<?php $this->load->view('templates/footer'); ?>
</div>


<?php if (isset($_SESSION['form_submit_scroll_id'])) { ?>
	<script>
		$(document).ready(function () {
			$("#<?= $_SESSION['form_submit_scroll_id'] ?>").get(0).scrollIntoView();
		});
	</script>
<?php } ?>


</body>
</html>
