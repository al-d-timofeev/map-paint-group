<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $APPLICATION;
$APPLICATION->SetPageProperty("title", "ГЕОГРАФИЯ");
$APPLICATION->SetTitle("ГЕОГРАФИЯ");
?>
<?if($id = $_GET['id']):?>
	<?
	$prop_gor_id = 12;
	if (CModule::IncludeModule('iblock')) {
		$iterator = CIBlockElement::GetPropertyValues(3, array('ACTIVE' => 'Y', 'ID' => $id), true, array('ID' => $prop_gor_id));
		while ($row = $iterator->Fetch()) {
			$searchcity = $row[$prop_gor_id];
		}
	}
?>
<?endif;?>
<div class="container bw geografiya-page">
	<div class="row zagolovok">
		<div class="col offset-xl-3 offset-lg-4">
			<h1><?$APPLICATION->ShowTitle(false);?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-3 col-lg-4">
			<p>
				 Собственная региональная сеть Paintgroup охватывает все ключевые регионы России, включая более 20 филиалов в крупнейших городах от Санкт-Петербурга до Владивостока и от Мурманска до Краснодара, а в общей сложности — более 30 точек присутствия.
			</p>
		</div>
		<div class="col-xl-9 col-lg-8">
			<div class="map-block">
				<div id="map" style="width: 100%; height: 600px">
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-3 col-lg-4">
			<div class="info-block-geografiya" id="info-block-geografiya" <?if($_GET['id']):?>data-searchcity="<?=$searchcity?>"<?endif;?>>

			</div>
		</div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
