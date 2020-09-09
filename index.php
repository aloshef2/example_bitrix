<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("тест");
?><?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
$IBLOCK_ID = 1;

$el = new CIBlockElement;
$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID));
while ($prop_fields = $properties->GetNext())
{
  print_r($prop_fields);
}
if (($handle = fopen("vacancy.csv", "r")) !== false) {

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {

        if ($row == 1) {
            $row++;
            continue;
        }
        $row++;


        $PROP['OFFICE'] = $data[1];
        $PROP['LOCATION'] = $data[2];
        $PROP['REQUIREMENTS'] = $data[3];
        $PROP['REQUIRE'] = array();
        $PROP['DUTIES'] = array();
        $PROP['CONDITIONS'] = array();
        $PROP['CONDITION'] = array();
        $PROP['TYPE'] = $data[8];
        $PROP['SALARY_VALUE'] = $data[7];
        $PROP['ACTIVITY'] = $data[9];
        $PROP['SCHEDULE'] = $data[10];
        $PROP['FIELD'] = $data[11];
        $PROP['EMAIL'] = $data[12];
        $PROP['DATE'] = date('d.m.Y');
        $PROP['SALARY_TYPE'] = '';

        if($data[4]){
            $requer = explode('•', $data[4]);
            foreach($requer as $key => $value){
                $PROP['REQUIRE'][$key] =array( "VALUE" => $value, "DESCRIPTION" => "test");
            }
        }
        if($data[5]){
            $requer = explode('•', $data[5]);
            foreach($requer as $key => $value){
                $PROP['DUTIES'][$key] =array( "VALUE" => $value);
            }
        }
        if($data[6]){
            $requer = explode('•', $data[6]);
            foreach($requer as $key => $value){
                $PROP['CONDITIONS'][$key] =array( "VALUE" => $value);
            }
        }
        if($data[6]){
            $requer = explode('•', $data[6]);
            foreach($requer as $key => $value){
                $PROP['CONDITION'][$key] =array( "VALUE" => $value);
            }
        }


        $arLoadProductArray = [
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $IBLOCK_ID,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $data[1],
            "ACTIVE" => end($data) ? 'Y' : 'N'
        ];

        ?>

        <pre>
            <? print_r($arLoadProductArray); ?>
        </pre>
        <?

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } else {
            echo "Error: " . $el->LAST_ERROR . '<br>';
        }
        fclose($handle);
    }
    
}


?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
