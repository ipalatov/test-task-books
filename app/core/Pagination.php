<?php

namespace App\core;

class Pagination
{
    public $currentPage;
    public $pageSize;
    public $total;
    public $countPages;
    public $uri;

    public function __construct($page, $pageSize, $total)
    {
        $this->pageSize = $pageSize; // размер страницы, количество записей на странице
        $this->total = $total; // всего записей
        $this->countPages = $this->getCountPages(); // количество страниц
        $this->currentPage = $this->getCurrenPage($page); // текущая страница
        $this->uri = $this->getParams(); // uri для сохранения других параметров запроса в строке(помимо номера страницы)
    }

    public function getCountPages()
    {
        $countPages = ceil($this->total / $this->pageSize) > 0 ? ceil($this->total / $this->pageSize) : 1;
        return $countPages;
    }

    public function getCurrenPage(int $page)
    {

        if (!$page || $page < 1) $page = 1;
        if ($page > $this->countPages) $page = $this->countPages;
        return $page;
    }

    // возвращает OFFSET для sql запроса 
    public function getOffset()
    {
        $offset = ($this->currentPage - 1) * $this->pageSize;
        return $offset;
    }

    // навигация
    public function getHTML()
    {
        $back = null;
        $forward =  null;
        $startPage = null;
        $endPage = null;
        $page2left = null;
        $page1left = null;
        $page2right = null;
        $page1right = null;

        if ($this->currentPage > 1) {
            $back = "<a href='{$this->uri}page=" . ($this->currentPage - 1) . "'> < </a>";
        }

        if ($this->currentPage < $this->countPages) {
            $forward = "<a href='{$this->uri}page=" . ($this->currentPage + 1) . "'> > </a>";
        }
        if ($this->currentPage > 3) {
            $startPage = "<a href='{$this->uri}page=1'> << </a>";
        }

        if ($this->currentPage < ($this->countPages - 2)) {
            $endPage = "<a href='{$this->uri}page={$this->countPages}'> >> </a>";
        }

        if ($this->currentPage - 2 > 0) {
            $page2left = "<a href='{$this->uri}page=" . ($this->currentPage - 2) . "'> " . ($this->currentPage - 2) . " </a>";
        }
        if ($this->currentPage - 1 > 0) {
            $page1left = "<a href='{$this->uri}page=" . ($this->currentPage - 1) . "'> " . ($this->currentPage - 1) . " </a>";
        }

        if ($this->currentPage + 2 <= $this->countPages) {
            $page2right = "<a href='{$this->uri}page=" . ($this->currentPage + 2) . "'> " . ($this->currentPage + 2) . " </a>";
        }

        if ($this->currentPage + 1 <= $this->countPages) {
            $page1right = "<a href='{$this->uri}page=" . ($this->currentPage + 1) . "'> " . ($this->currentPage + 1) . " </a>";
        }
        return $startPage . $back . $page2left . $page1left . '<b>' . $this->currentPage . '</b>' . $page1right . $page2right . $forward . $endPage;
    }

    // сохранение других параметров запроса в строке(помимо номера страницы)
    public function getParams()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('?', $url);
        $uri = $url[0] . '?';
        if (isset($url[1]) && $url[1] != '') {
            $params = explode('&', $url[1]);
            foreach ($params as $param) {
                if (!preg_match('#page=#', $param)) $uri .= "{$param}&amp;";
            }
        }
        return $uri;
    }
}
