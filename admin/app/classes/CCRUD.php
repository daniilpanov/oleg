<?php
namespace app\classes;


class CCRUD extends MCRUD
{
    public function getPagesList()
    {
        $result = $this->preparePagesList();
        $pages = $result->fetchAll();

        return $pages;
    }

    public function editPage($id)
    {
        $result = $this->getPageDataForEdit($id);
        $page = $result->fetch();
        return $page;
    }

    public function updatePage($id, $data)
    {
        $this->updatePageData($id, $data);
    }
}