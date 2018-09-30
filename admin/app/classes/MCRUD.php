<?php
namespace app\classes;


class MCRUD
{
    protected function preparePagesList()
    {
        $result = Db::getInstance()
            ->read("pages", "id, menu_name, menu_icon");
        return $result;
    }

    protected function getPageDataForEdit($id)
    {
        $result = Db::getInstance()
            ->read("pages", "menu_name,content,title,menu_icon", array("id"=>$id));
        return $result;
    }

    protected function updatePageData($id,$data)
    {
        if(Db::getInstance()->update("pages", $data, array('id'=>$id)))
        {
            echo "<span class='green'><br><br>Данные успешно обновлены!!!</span>";
            header( 'Refresh:2; URL=' );
        }
    }
}