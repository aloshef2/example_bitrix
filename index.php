<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("тест");
?><?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
$IBLOCK_ID = 3;

$el = new CIBlockElement;

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
        $PROP['REQUIRE']['VALUES'] = explode('• ', $data[4]);
        $PROP['DUTIES']['VALUES'] = explode('• ', $data[5]);
        $PROP['CONDITIONS']['VALUES'] = explode('• ', $data[6]);
        $PROP['CONDITION']['VALUES'] = explode('• ', $data[6]);
        $PROP['TYPE'] = $data[8];
        $PROP['SALARY_VALUE'] = $data[7];
        $PROP['ACTIVITY'] = $data[9];
        $PROP['SCHEDULE'] = $data[10];
        $PROP['FIELD'] = $data[11];
        $PROP['EMAIL'] = $data[12];
        $PROP['DATE'] = date('d.m.Y');
        $PROP['SALARY_TYPE'] = '';


        $arLoadProductArray = [
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $IBLOCK_ID,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $data[0],
            "ACTIVE" => end($data) ? 'Y' : 'N'
        ];

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } else {
            echo "Error: " . $el->LAST_ERROR . '<br>';
        }
    }
    fclose($handle);
}


?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>