<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

    function dump($var)
    {
        global $USER;
        if ($USER->IsAdmin()) {
            echo '<pre>';
            print_r($var);
            echo '</pre>';
        }
    }


    AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("MyClass", "OnAfterIBlockElementAddHandler"));
    AddEventHandler("iblock", "OnAfterIBlockElementDelete", Array("MyClass", "OnAfterIBlockElementDeleteHandler"));
    AddEventHandler("iblock", "OnIBlockElementUpdate", Array("MyClass", "OnIBlockElementUpdateHandler"));

    class MyClass
    {

        function OnAfterIBlockElementAddHandler(&$arFields)
        {
            if ($arFields['IBLOCK_ID'] == 3) {

                $adr = $arFields['PROPERTY_VALUES']['5']['n0']['VALUE'];
                $podr = $arFields['PROPERTY_VALUES']['7']['n0']['VALUE'];
                $open = $arFields['PROPERTY_VALUES']['8']['n0']['VALUE'];
                $close = $arFields['PROPERTY_VALUES']['9']['n0']['VALUE'];
                $tel = $arFields['PROPERTY_VALUES']['10']['n0']['VALUE'];
                $dir = $arFields['PROPERTY_VALUES']['11']['0']['VALUE'];
                $email = $arFields['PROPERTY_VALUES']['14']['n0']['VALUE'];
                $http = 'https://geocode-maps.yandex.ru/1.x?format=json&geocode=' . urlencode($adr) . '&apikey=15d9037b-546f-4acb-83c3-d83b108a32d3';

                $gc_address = json_decode(file_get_contents($http), true);
//			$city = $gc_address['response']['GeoObjectCollection']['featureMember']['0']['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['AdministrativeAreaName'];
                $coordinates = explode(" ", $gc_address['response']['GeoObjectCollection']['featureMember']['0']['GeoObject']['Point']['pos']);

                foreach ($gc_address['response']['GeoObjectCollection']['featureMember']['0']['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'] as $item) {
                    if ($item['name'] == 'Москва') {
                        $city = 'Москва';
                        break;
                    } elseif ($item['name'] == 'Санкт-Петербург') {
                        $city = 'Санкт-Петербург';
                        break;
                    } elseif ($item['name'] == 'Севастополь') {
                        $city = 'Севастополь';
                        break;
                    } else {
                        if ($item['kind'] == 'locality') {
                            $city = $item['name'];
                        }
                    }
                }


                $el = new CIBlockElement;
                $PROP = array();
                $PROP[6] = $coordinates[1] . ", " . $coordinates[0];
                $PROP[5] = $adr;
                $PROP[12] = $city;
                $PROP[7] = $podr;
                $PROP[8] = $open;
                $PROP[9] = $close;
                $PROP[10] = $tel;
                $PROP[11] = $dir;
                $PROP[14] = $email;

                $arLoadProductArray = Array(
                    "PROPERTY_VALUES" => $PROP
                );

                $PRODUCT_ID = $arFields['ID'];
                $res = $el->Update($PRODUCT_ID, $arLoadProductArray);

				//от файла отказываемся 23/01/2020
                $datadown = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/about-the-group/geographical/data.json'), true);
                $dataup = $_SERVER["DOCUMENT_ROOT"] . '/about-the-group/geographical/data.json';

                if ($dir == '13') {
                    $dir_name = 'Тверской лакокрасочный завод';
                    $preset = 'islands#darkOrangeDotIcon';
                } elseif ($dir == '11') {
                    $dir_name = 'Авторемонтные системы';
                    $preset = 'islands#darkBlueDotIcon';
                } elseif ($dir == '12') {
                    $dir_name = 'Транслак';
                    $preset = 'islands#orangeDotIcon';
                } elseif ($dir == '14') {
                    $dir_name = 'МалерМаркет';
                    $preset = 'islands#redDotIcon';
                }
                $pointmap = array(
                    "type" => "Feature",
                    "id" => $PRODUCT_ID,
                    "geometry" => array(
                        "type" => "Point",
                        "coordinates" => [$coordinates[1], $coordinates[0]]),
                    "properties" => array(
                        "balloonContentHeader" => '<font size="4"><b>' . $dir_name . '</b></font>',
                        "balloonContentBody" => '<p><i>' . $podr . ': </i><span style="white-space: nowrap">' . $adr . '</span></p><p><i>Почта: </i><a href="mailto:' . $email . '">' . $email . '</a></p><p><i>Тел.: </i>' . $tel . '</p>',
                        "balloonContentFooter" => '<font size="2">Открыто с ' . $open . ' до ' . $close . '</font>',
                        "clusterCaption" => '<font size="3">' . $dir_name . '</font>',
                        "city" => $city),
                    "options" => array(
                        "preset" => $preset,
                    ));

                array_push($datadown['features'], $pointmap);
                file_put_contents($dataup, json_encode($datadown));


//			$keys = array();
//
//			foreach ($arFields['PROPERTY_VALUES']['11']['0'] as $key => $value) {
//				array_push($keys, $value);
//			}
//
//			$file = $_SERVER["DOCUMENT_ROOT"] . '/file.php';
//			file_put_contents($file, $arFields['PROPERTY_VALUES']['11']['0']['VALUE']);

            }

            if ($arFields['IBLOCK_ID'] == 5) {

                if($arFields['PROPERTY_VALUES']['28'])
                {
                    $adr = $arFields['PROPERTY_VALUES']['22']['n0']['VALUE'];
                    $podr = $arFields['PROPERTY_VALUES']['24']['n0']['VALUE'];
                    $open = $arFields['PROPERTY_VALUES']['25']['n0']['VALUE'];
                    $close = $arFields['PROPERTY_VALUES']['26']['n0']['VALUE'];
                    $tel = $arFields['PROPERTY_VALUES']['27']['n0']['VALUE'];
                    $dir = $arFields['PROPERTY_VALUES']['28']['0']['VALUE'];
                    $email = $arFields['PROPERTY_VALUES']['30']['n0']['VALUE'];
                }
                elseif ($arFields['PROPERTY_VALUES']['DIR'])
                {
                    $adr = $arFields['PROPERTY_VALUES']['ADDRESS'];
                    $podr = $arFields['PROPERTY_VALUES']['PODR'];
                    $open = $arFields['PROPERTY_VALUES']['OPEN'];
                    $close = $arFields['PROPERTY_VALUES']['CLOSE'];
                    $tel = $arFields['PROPERTY_VALUES']['TEL'];
                    $dir = $arFields['PROPERTY_VALUES']['DIR'];
                    $email = $arFields['PROPERTY_VALUES']['EMAIL'];
                }



                $http = 'https://geocode-maps.yandex.ru/1.x?format=json&geocode=' . $adr . '&apikey=15d9037b-546f-4acb-83c3-d83b108a32d3&lang=en';
                $gc_address = json_decode(file_get_contents($http), true);
                $coordinates = explode(" ", $gc_address['response']['GeoObjectCollection']['featureMember']['0']['GeoObject']['Point']['pos']);
                $adr_en = $gc_address['response']['GeoObjectCollection']['featureMember']['0']['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];


                foreach ($gc_address['response']['GeoObjectCollection']['featureMember']['0']['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'] as $item) {
                    if ($item['name'] == 'Moscow') {
                        $city = 'Moscow';
                        break;
                    } elseif ($item['name'] == 'St. Petersburg') {
                        $city = 'St. Petersburg';
                        break;
                    } elseif ($item['name'] == 'Sevastopol') {
                        $city = 'Sevastopol';
                        break;
                    } else {
                        if ($item['kind'] == 'locality') {
                            $city = $item['name'];
                        }
                    }
                }


                if ($dir == '50') {
                    $dir_name = 'Tver paint factory';
                    $preset = 'islands#darkOrangeDotIcon';
                } elseif ($dir == '51') {
                    $dir_name = 'Auto repair systems';
                    $preset = 'islands#darkBlueDotIcon';
                } elseif ($dir == '52') {
                    $dir_name = 'Translac';
                    $preset = 'islands#orangeDotIcon';
                } elseif ($dir == '53') {
                    $dir_name = 'MalerMarket';
                    $preset = 'islands#redDotIcon';
                }

                $el = new CIBlockElement;
                $PROP = array();
                $PROP[23] = $coordinates[1] . ", " . $coordinates[0];
                $PROP[22] = $adr;
                $PROP[29] = $city;
                $PROP[24] = $podr;
                $PROP[25] = $open;
                $PROP[26] = $close;
                $PROP[27] = $tel;
                $PROP[28] = $dir;
                $PROP[30] = $email;
                $PROP[31] = $dir_name;
                $PROP[33] = $adr_en;

                $arLoadProductArray = Array(
                    "PROPERTY_VALUES" => $PROP
                );

                $PRODUCT_ID = $arFields['ID'];
                $res = $el->Update($PRODUCT_ID, $arLoadProductArray);

	            //от файла отказываемся 23/01/2020
                $datadown = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/en/about-the-group/geographical/data.json'), true);
                $dataup = $_SERVER["DOCUMENT_ROOT"] . '/en/about-the-group/geographical/data.json';


                $pointmap = array(
                    "type" => "Feature",
                    "id" => $PRODUCT_ID,
                    "geometry" => array(
                        "type" => "Point",
                        "coordinates" => [$coordinates[1], $coordinates[0]]),
                    "properties" => array(
                        "balloonContentHeader" => '<font size="4"><b>' . $dir_name . '</b></font>',
                        "balloonContentBody" => '<p><i>' . $podr . ': </i><span style="white-space: nowrap">' . $adr_en . '</span></p><p><i>Email: </i><a href="mailto:' . $email . '">' . $email . '</a></p><p><i>Tel.: </i>' . $tel . '</p>',
                        "balloonContentFooter" => '<font size="2">Open from ' . $open . ' to ' . $close . '</font>',
                        "clusterCaption" => '<font size="3">' . $dir_name . '</font>',
                        "city" => $city),
                    "options" => array(
                        "preset" => $preset,
                    ));
                array_push($datadown['features'], $pointmap);
                file_put_contents($dataup, json_encode($datadown));


//                    AddMessage2Log($arFields);

            }
        }

        function OnAfterIBlockElementDeleteHandler($arFields)
        {
            if ($arFields['IBLOCK_ID'] == 3) {

                $file = $_SERVER["DOCUMENT_ROOT"] . '/file.php';
	            //от файла отказываемся 23/01/2020
                $datadown = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/about-the-group/geographical/data.json'), true);
                $dataup = $_SERVER["DOCUMENT_ROOT"] . '/about-the-group/geographical/data.json';

                foreach ($datadown['features'] as $key => $elem) {
                    if (intval($elem['id']) == $arFields['ID']) {
                        unset($datadown['features'][$key]);
                    }
                }
                sort($datadown['features']);
                file_put_contents($dataup, json_encode($datadown));
            }

            if ($arFields['IBLOCK_ID'] == 5) {
	            //от файла отказываемся 23/01/2020
                $datadown = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/en/about-the-group/geographical/data.json'), true);
                $dataup = $_SERVER["DOCUMENT_ROOT"] . '/en/about-the-group/geographical/data.json';

                foreach ($datadown['features'] as $key => $elem) {
                    if (intval($elem['id']) == $arFields['ID']) {
                        unset($datadown['features'][$key]);
                    }
                }
                sort($datadown['features']);
                file_put_contents($dataup, json_encode($datadown));
            }

        }

        function OnIBlockElementUpdateHandler(&$arFields)
        {
            if ($arFields['IBLOCK_ID'] == 3) {
	            //от файла отказываемся 23/01/2020
                $datadown = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/about-the-group/geographical/data.json'), true);
                $dataup = $_SERVER["DOCUMENT_ROOT"] . '/about-the-group/geographical/data.json';

                if ($arFields['ACTIVE'] == "Y") {
                    foreach ($datadown['features'] as $key => $elem) {
                        if (intval($elem['id']) == $arFields['ID']) {
                            $datadown['features'][$key]["geometry"]["type"] = "Point";
                            break;
                        }
                    }
                } else {
                    foreach ($datadown['features'] as $key => $elem) {
                        if (intval($elem['id']) == $arFields['ID']) {
                            $datadown['features'][$key]["geometry"]["type"] = 'deactive';
                            break;
                        }
                    }
                }
                file_put_contents($dataup, json_encode($datadown));
            }

            if ($arFields['IBLOCK_ID'] == 5) {
	            //от файла отказываемся 23/01/2020
                $datadown = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/en/about-the-group/geographical/data.json'), true);
                $dataup = $_SERVER["DOCUMENT_ROOT"] . '/en/about-the-group/geographical/data.json';

                if ($arFields['ACTIVE'] == "Y") {
                    foreach ($datadown['features'] as $key => $elem) {
                        if (intval($elem['id']) == $arFields['ID']) {
                            $datadown['features'][$key]["geometry"]["type"] = "Point";
                            break;
                        }
                    }
                } else {
                    foreach ($datadown['features'] as $key => $elem) {
                        if (intval($elem['id']) == $arFields['ID']) {
                            $datadown['features'][$key]["geometry"]["type"] = 'deactive';
                            break;
                        }
                    }
                }
                file_put_contents($dataup, json_encode($datadown));
            }
        }
    }


    AddEventHandler("form", "onBeforeResultAdd", "checkGoogleCaptcha");

    function checkGoogleCaptcha($WEB_FORM_ID, &$arFields, &$arrVALUES)
    {

        global $APPLICATION;

        if ($_POST['g-recaptcha-response']) {
            $httpClient = new \Bitrix\Main\Web\HttpClient;
            $result = $httpClient->post(
                'https://www.google.com/recaptcha/api/siteverify',
                array(
                    'secret' => '6Le05p0UAAAAALE0vFpWeUd4RZSQ87zxCejYzUz3',
                    'response' => $_POST['g-recaptcha-response'],
                    'remoteip' => $_SERVER['HTTP_X_REAL_IP']
                )
            );
            $result = json_decode($result, true);
            if ($result['success'] !== true) {
                $APPLICATION->throwException("Вы не прошли проверку подтверждения личности");
                return false;
            }
        } else {
            $APPLICATION->throwException("Вы не прошли проверку подтверждения личности");
            return false;
        }
    }