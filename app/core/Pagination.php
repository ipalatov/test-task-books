<?php

namespace App\core;

class Pagination
{

    /**
     * Текущая страница
     * 
     * @var int 
     */
    public $currentPage;

    /**
     * Количество элементов на странице
     * 
     * @var int 
     */

    public $pageSize;

    /**
     * Всего элементов
     * 
     * @var int
     */

    public $total;

    /**
     * Количество страниц
     * 
     * @var int
     */
    public $countPages;

    /**
     * URI для сохранения других параметров запроса в строке(помимо номера страницы)
     * 
     * @var string 
     */
    public $uri;

    /**
     * @param int $page Страница, полученная из GET запроса
     * @param int $pageSize Заданный размер страницы (количество элементов на странице)
     * @param int $total Всего элементов
     */
    public function __construct($page, $pageSize, $total)
    {
        $this->pageSize = $pageSize;
        $this->total = $total;
        $this->countPages = $this->getCountPages();
        $this->currentPage = $this->getCurrenPage($page);
        $this->uri = $this->getParams();
    }

    /**
     * Вычисляет и возвращает количество страниц
     * 
     * @return int
     */
    public function getCountPages()
    {
        $countPages = ceil($this->total / $this->pageSize) > 0 ? (int) ceil($this->total / $this->pageSize) : 1;
        return $countPages;
    }

    /**
     * Вычисляет и возвращает текущую страницу
     * 
     * @return int
     */
    public function getCurrenPage(int $page)
    {

        if (!$page || $page < 1) $page = 1;
        if ($page > $this->countPages) $page = $this->countPages;
        return $page;
    }

    /**
     * Вычисляет и возвращает OFFSET для sql запроса
     * 
     * @return int
     */
    public function getOffset()
    {
        $offset = ($this->currentPage - 1) * $this->pageSize;
        return $offset;
    }

    /**
     * Возвращает HTML-код кнопок навигации
     * 
     * @return string
     */
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
            $back = "<a class='btn btn-secondary' href='{$this->uri}page=" . ($this->currentPage - 1) . "'> < </a>";
        }

        if ($this->currentPage < $this->countPages) {
            $forward = "<a class='btn btn-secondary' href='{$this->uri}page=" . ($this->currentPage + 1) . "'> > </a>";
        }
        if ($this->currentPage > 3) {
            $startPage = "<a class='btn btn-secondary' href='{$this->uri}page=1'> << </a>";
        }

        if ($this->currentPage < ($this->countPages - 2)) {
            $endPage = "<a class='btn btn-secondary' href='{$this->uri}page={$this->countPages}'> >> </a>";
        }

        if ($this->currentPage - 2 > 0) {
            $page2left = "<a class='btn btn-secondary' href='{$this->uri}page=" . ($this->currentPage - 2) . "'> " . ($this->currentPage - 2) . " </a>";
        }
        if ($this->currentPage - 1 > 0) {
            $page1left = "<a class='btn btn-secondary' href='{$this->uri}page=" . ($this->currentPage - 1) . "'> " . ($this->currentPage - 1) . " </a>";
        }

        if ($this->currentPage + 2 <= $this->countPages) {
            $page2right = "<a class='btn btn-secondary' href='{$this->uri}page=" . ($this->currentPage + 2) . "'> " . ($this->currentPage + 2) . " </a>";
        }

        if ($this->currentPage + 1 <= $this->countPages) {
            $page1right = "<a class='btn btn-secondary' href='{$this->uri}page=" . ($this->currentPage + 1) . "'> " . ($this->currentPage + 1) . " </a>";
        }
        $this->currentPage = "<a class='btn btn-secondary' href='{$this->uri}page=" . $this->currentPage  . "'> " . $this->currentPage . " </a>";

        $startPageHtml = '<li class="page-item">' . $startPage . '</li>';
        $backHtml = '<li class="page-item">' . $back . '</li>';
        $page2leftHtml = '<li class="page-item">' . $page2left . '</li>';
        $page1leftHtml = '<li class="page-item">' . $page1left . '</li>';
        $this->currentPageHtml = '<li class="page-item">' . $this->currentPage . '</li>';
        $page1rightHtml = '<li class="page-item">' . $page1right . '</li>';
        $page2rightHtml = '<li class="page-item">' . $page2right . '</li>';
        $forwardHtml = '<li class="page-item">' . $forward . '</li>';
        $endPageHtml = '<li class="page-item">' . $endPage . '</li>';

        $paginationHtml = '
        <nav aria-label="pagination">
            <ul class="pagination">' .
            $startPageHtml . $backHtml . $page2leftHtml . $page1leftHtml . $this->currentPageHtml . $page1rightHtml . $page2rightHtml
            . $forwardHtml . $endPageHtml .
            '</ul>
        </nav>';

        return $paginationHtml;
    }

    /**
     * Получает другие параметры запроса в строке(помимо номера страницы page=) 
     * с целью не потерять их при навигации между страницами
     * 
     * @return string
     */
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
