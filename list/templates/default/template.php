<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */

foreach($arResult as $key=>$value){
	if($value['Dir']){
		print_r($value);
	}
	if($value['File']){
		?>
			<input name="update" value="<?=$value['name'];  ?>" form="<?=$value['resource_id'] ?>" />
			<form method="post"><input name="delete" value="<?=$value['path']?>" hidden/> <button type="submit">Delete</button></form>
			<form id='<?=$value['resource_id']?>' action="" method="post"><input name="path" value='<?=$value['path']?>' hidden /><input name="name" value="<?=$value['name']?>" hidden/> <button type="submit">Update</button></form>
		<?
	}
}

