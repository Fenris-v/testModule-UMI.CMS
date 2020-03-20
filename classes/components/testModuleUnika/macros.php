<?php

class testModuleUnikaMacros
{
    /**
     * @var testModuleUnika $module
     */
    public $module;

    /**
     * Возвращает данные страницы
     * @param string $template имя шаблона (для tpl)
     * @param bool|int $pageId идентификатор страниц, если не передан - возьмет текущую страницу.
     * @return mixed
     * @throws publicAdminException
     */

    public function page($template = 'default', $pageId = false)
    {
        // Загрузка блоков шаблона для tpl
        list($templateBlock) = testModuleUnika::loadTemplates('testModuleUnika/' . $template, 'block');

        // Если страница не передана - берется текущая
        if (!is_numeric($pageId)) {
            $pageId = cmsController::getInstance()->getCurrentElementId();
        }

        $umiHierarchy = umiHierarchy::getInstance();
        $page = $umiHierarchy->getElement($pageId);

        // Если страница не существует - исключение
        if (!$page instanceof iUmiHierarchyElement) {
            throw new publicAdminException(getLabel('error-page-not-found', 'testModuleUnika'));
        }

        // Формирование данных
        $pageData = [
            'id' => $pageId,
            'link' => $umiHierarchy->getPathById($pageId)
        ];

        // Уведомление панели редактирования о доступной странице
        testModuleUnika::pushEditable('testModuleUnika', 'page', $pageId);

        // Применение шаблона и возвращение результата
        return testModuleUnika::parseTemplate($templateBlock, $pageData, $pageId);
    }

    /**
     * Возвращает список страниц
     * @param string $template имя шаблона (для tpl)
     * @param bool|int $limit ограничение на количество, если не передано - возьмет из настроек.
     * @return mixed
     * @throws selectorException
     */
    public function pageList($template = 'default', $limit = false)
    {
        // Загрузка блоков шаблона для tpl
        list($templateBlock, $templateLine, $templateEmpty) = testModuleUnika::loadTemplates(
            'testModuleUnika/' . $template,
            'pages_list_block',
            'pages_list_line',
            'pages_list_block_empty'
        );

        // Если не предано ограничение - берется из настроек модуля в реестре
        if (!is_numeric($limit)) {
            $limit = (int) regedit::getInstance()->getVal($this->module->pagesLimitXpath);
        }

        // Получение настроек постраничного вывода
        $pageNumber = (int) getRequest('p');
        $offset = $limit * $pageNumber;

        // Добавление выборки
        $pages = new selector('pages');
        $pages->types('object-type')->name('testModuleUnika', 'page');
        $pages->limit($offset, $limit);

        $result = $pages->result();
        $total = $pages->length();

        // Если страниц нет - применяется шаблон $templateEmpty и возвращается результат
        if ($total == 0) {
            return testModuleUnika::parseTemplate($templateEmpty, []);
        }

        $items = [];
        $data = [];

        /**
         * @var iUmiHierarchyElement|iUmiEntinty $page
         */
        // Формирование данных каждой страницы и применение к каждой шаблона $templateLine
        foreach ($result as $page) {
            $item = [];
            $item['attribute:id'] = $page->getId();
            $item['attribute:name'] = $page->getName();
            $items[] = testModuleUnika::parseTemplate($templateLine, $item);
        }

        // Формируем общие данные работы макроса
        $data['subnodes:items'] = $items;
        $data['total'] = $total;

        // Применяем общий шаблон $templateBlock и возвращаем результат
        return testModuleUnika::parseTemplate($templateBlock, $data);
    }
}