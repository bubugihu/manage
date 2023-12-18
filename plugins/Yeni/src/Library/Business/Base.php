<?php

namespace Yeni\Library\Business;

use Cake\ORM\TableRegistry;

abstract class Base
{
    /**
     * Instantinates the class
     */
    public function __construct() {

    }

    /**
     * Gets data provider.
     *
     * @param string $providerName name of provider
     * @return entity
     */
    protected function _getProvider($providerName) {
        return TableRegistry::getTableLocator()->get($providerName);
    }

    protected function upload_image($file_input, $input_field = 'banner', $params, $type)
    {
            //Check if image has been uploaded
            if (!empty($file_input[$input_field]['name'])) {
                $file = $file_input[$input_field];

                $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                $arr_ext = array('jpg', 'jpeg', 'png');

                if (in_array($ext, $arr_ext)) {
                    if (!file_exists('img/jlpt')) {
                        mkdir('img/jlpt', 0777, true);
                    }
                    $img = strtoupper($params['level']) . "_" . $this->formatName($params) . "_" . $type . "_" . $this->randomString() . "." . $ext;
                    if(file_exists(WWW_ROOT . "img/" .$img))
                    {
                        unlink(WWW_ROOT . "img/jlpt/" .$img);
                    }
                    move_uploaded_file($file['tmp_name'], WWW_ROOT . "img/jlpt/" .$img);

                    return $img;
                }
            }
        return false;
    }

    private function formatName($params)
    {
        $name = $params['last_name'] . $params['first_name'];
        $result = ucwords(trim($name));
        return str_replace(' ', '', $result);
    }

    private function randomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        $randstring .= $characters[rand(10, strlen($characters))];
        return $randstring;
    }
}
