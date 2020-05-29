<?if ($_REQUEST["IBLOCK_ID"] == 3):?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="/local/templates/paintgroup/js/jquery.maskedinput.js"></script>
	<script>
		$(document).ready(function(){
			$(function(){
				$("#tr_PROPERTY_10 input").mask("+7 (999) 999-99-99");
			});
		});
	</script>
<?endif;?>
<?if ($_REQUEST["IBLOCK_ID"] == 1):?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="/local/templates/paintgroup/js/jquery.maskedinput.js"></script>
<!--	<script>-->
<!--		$(document).ready(function(){-->
<!--			$(function(){-->
<!--				$("#tr_PROPERTY_3 textarea").attr('maxlength', 250);-->
<!--			});-->
<!--			$("#tr_PROPERTY_3 .adm-detail-valign-top").append('<p><small>(количество оставшихся символов: <b></b>)</small></p>');-->
<!--			var maxlength = 250,-->
<!--				length = $("#tr_PROPERTY_3 textarea").val().length,-->
<!--				result = maxlength - length;-->
<!--				$("#tr_PROPERTY_3 .adm-detail-valign-top p b").html(result);-->
<!--			$("#tr_PROPERTY_3 textarea").keyup(function () {-->
<!--				length = this.value.length;-->
<!--				result = maxlength - length;-->
<!--				$("#tr_PROPERTY_3 .adm-detail-valign-top p b").html(result);-->
<!--			});-->
<!--		});-->
<!--	</script>-->
<?endif;?>