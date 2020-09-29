<?php
require_once 'vendor/autoload.php';

error_reporting(0);

$disk = new Arhitector\Yandex\Disk('AgAAAABEPtbVAAaeyJMCYdbEFkC0tVgyrgWUKZg');
/**
 * Получить Объектно Ориентированное представление закрытого ресурса.
 * @var  Arhitector\Yandex\Disk\Resource\Closed $resource
*/
$resource = $disk->getResource('/');

var_dump($_POST);

if($_POST['delete']){
  echo $_POST['delete'];
  deleteFile($_POST['delete'],$disk);
}
if($_POST['update']){
  echo "update";
  $publicResource = $disk->getResource($_POST['path']);
  if(file_exists(__DIR__.'/upload/'.$_POST['name'])){
    unlink(__DIR__.'/upload/'.$_POST['name']);
  }
  $publicResource->download(__DIR__.'/upload/'.$_POST['name']);
  rename(__DIR__.'/upload/'.$_POST['name'],__DIR__.'/upload/'.$_POST['update']);
  updateFile($_POST['update'],$_POST['path'], $disk);
  unlink(__DIR__.'/upload/'.$_POST['update']);
}
$resource = getElement('/');
?>

<form method="post" enctype="multipart/form-data">
  <input type="file" name="file">
  <button type="submit">Pfuhepbnm</button>
</form>

<?php

echo'<div class="" style="display: flex; flex-direction: column;">';
showResurce($resource);
echo"</div>";


if(isset($_FILES)){
  $tmp_file = $_FILES['file']['tmp_name'];
  $file_name = $_FILES['file']['name'];
  move_uploaded_file($tmp_file, 'upload/'.$file_name);
  addFile($file_name, $disk);
}

function getElement($path){
  $disk = new Arhitector\Yandex\Disk('AgAAAABEPtbVAAaeyJMCYdbEFkC0tVgyrgWUKZg');
  $resource = $disk->getResource($path);
  return $resource;
}

function showResurce($item){
  foreach ($item->items as $item)
  {
    if($item->isFile()){
      showFile($item);
      //echo '<pre>'; print_r($item); echo '</pre>'; die();
    }
    if($item->isDir()){
      $resource = getElement($item['path'].'/');
      echo '<div style=\'background: red; padding-left: 15px;\'>  '.$item['name']; echo '</div>';
      showResurce($resource);
      echo '</br>';
    }
  }
}
function showFile($item){
  echo '<div style=\'background: whitesmoke\'>';
  echo "<input name=\"update\" value=".$item['name']." form=".$item['resource_id']." '/>";
  echo '<form action="" method="post"><input name="delete" value="'.$item['path'].'" hidden/> <button type="submit">Delete</button></form>';
  echo '<form id='.$item['resource_id'].' action="" method="post"><input name="path" value='.$item['path'].' hidden /><input name="name" value="'.$item['name'].'" hidden/> <button type="submit">Update</button></form>';
  echo '</div>';
}

function addFile($file ,$disk){
  $resource = $disk->getResource($file);
  if(!$resource->has())
  {
      return;
  }
  $resource->upload(__DIR__.'/upload/'.$file);
}


function deleteFile($file ,$disk){
  $resource = $disk->getResource($file);
  if(!$resource->has())
  {
      return;
  }
  $resource->delete($file, true);
}

function updateFile($newfil, $path ,$disk){
  deleteFile($path, $disk);
  $resource = $disk->getResource($newfil);
  $resource->upload(__DIR__.'/upload/'.$newfil);
}
?>