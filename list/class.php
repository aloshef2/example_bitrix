<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\IO,
    Bitrix\Main\Application;

class YADisk extends CBitrixComponent
{

	public function getElement($path){
		$disk = new Arhitector\Yandex\Disk('AgAAAABEPtbVAAafYn9B2FNpk03gj2LdF6cOI-A');
		$resource = $disk->getResource($path);
		return $resource;
	}

	public function showResurce($item, $dir = null){
		foreach ($item->items as $item)
		{
			if($item->isFile()){
			$arFile = ["name" => $item['name'], "resource_id" => $item['resource_id'], "path" => $item['path'], 'Dir' => false, "File" => true];
			$this->arResult['File'] = $arFile;
			$this->arResult['Dir'] = $dir;
			$this->includeComponentTemplate();
			}
			if($item->isDir()){
			$resource = $this->getElement($item['path'].'/');
			$arDir = ["name" => $item['name'], "resource_id" => $item['resource_id'], "path" => $item['path'], 'Dir' => true, "File" => false];
			$this->arResult['Dir'] = $arDir;
			$this->arResult['File'] = null;
			$this->includeComponentTemplate();
			$this->showResurce($resource, $arDir);
			}
		}
	}

	public function addFile($file, $path ,$disk){
		$resource = $disk->getResource($file);
		if($resource->has())
		{
			return;
		}
		$resource->upload($_SERVER["DOCUMENT_ROOT"]. $path);
		header("Refresh:0");
	}

	public function deleteFile($file ,$disk){
		$resource = $disk->getResource($file);
		if(!$resource->has())
		{
			return;
		}
		$resource->delete($file, true);
	  }
	  
	 public function updateFile($newfil, $path ,$disk){
		$this->deleteFile($path, $disk);
		$resource = $disk->getResource($newfil);
		$resource->upload($_SERVER["DOCUMENT_ROOT"]."/upload/tmp/".$newfil);
		IO\File::deleteFile($_SERVER["DOCUMENT_ROOT"]."/upload/tmp/".$newfil);
	  }

	public function executeComponent()
	{
		$disk = new Arhitector\Yandex\Disk('AgAAAABEPtbVAAafYn9B2FNpk03gj2LdF6cOI-A');
		try
		{
			
			$this->includeComponentTemplate('addButton');
			$resource = $this->getElement('/');
			$this->showResurce($resource);
		}
		catch (Exception $e)
		{
			$e->getMessage();
		}

		try
		{
			if(isset($_FILES)){
				print_r($_FILES['file']['name']);
				$arr_file = Array(
					"name" => $_FILES['file']['name'],
					"size" => $_FILES['file']['size'],
					"tmp_name" => $_FILES['file']['tmp_name'],
					"type" => $_FILES['file']['type'],
					"old_file" => "",
					"del" => "Y",
					"MODULE_ID" => ""
				);
				$file = new IO\File(Application::getDocumentRoot() . "/user/".$_FILES['file']['name']);
				if($file->isExists()){
					return;
				}
				$fid = CFile::SaveFile($arr_file, "/user/");
				$path = CFile::GetPath($fid);
				$fileName = $_FILES['file']['name'];
				$this->addFile($fileName, $path, $disk);
				//CFile::Delete($arForm["IMAGE_ID"]);
			}
		}
		catch (Exception $e)
		{
			$e->getMessage();
		}

		try
		{
			if($_POST['delete']){
				$this->deleteFile($_POST['delete'],$disk);
				header("Refresh:0");
			  }
		}
		catch (Exception $e)
		{
			$e->getMessage();
		}

		try
		{
			if($_POST['update']){
				$resource = $this->getElement($_POST['path']);
				//print_r($resource);
				$resource->toArray();
				$resource->download($_SERVER["DOCUMENT_ROOT"]."/upload/tmp/".$_POST['name']);
				rename($_SERVER["DOCUMENT_ROOT"]."/upload/tmp/".$_POST['name'],$_SERVER["DOCUMENT_ROOT"]."/upload/tmp/".$_POST['update']);
				$this->updateFile($_POST['update'],$_POST['path'], $disk);
				header("Refresh:0");
			  }
		}
		catch (Exception $e)
		{
			$e->getMessage();
		}
	}
}
