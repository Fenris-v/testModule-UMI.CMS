<?php

use UmiCms\Service;

/** Класс функицонала админ панели */
class testModuleUnikaAdmin
{

    use baseModuleAdmin;

    /** @var testModuleUnika $module */
    public $module;

    /**
     * Возвращает список страниц
     * @return bool
     * @throws coreException
     * @throws selectorException
     */
    public function pages()
    {
        // Установка типа данных (список)
        $this->setDataType('list');
        // Установка типа операции (просмотр)
        $this->setActionType('view');

        // Если метод вызван не через /.xml - ничего не возвращаем
        if ($this->module->ifNotXmlMode()) {
            $this->setDirectCallError();
            $this->doData();
            return true;
        }

        // Получаем настройки постраничного вывода
        $limit = getRequest('per_page_limit');
        $pageNumber = (int)getRequest('p');
        $offset = $limit * $pageNumber;

        // Выборка страниц модуля
        $pages = new selector('pages');
        $pages->types('object-type')->name('testModuleUnika', 'page');
        $pages->limit($offset, $limit);

        // Фильтры и сортировка
        selectorHelper::detectHierarchyFilters($pages);
        selectorHelper::detectWhereFilters($pages);
        selectorHelper::detectOrderFilters($pages);

        $result = $pages->result();
        $total = $pages->length();

        // Устанавливаем результаты работы метода
        $this->setDataRange($limit, $offset);
        $data = $this->prepareData($result, 'pages');
        $this->setData($data, $total);
        $this->doData();
    }

    /**
     * Возвращает данные для построения формы добавления страницы модуля.
     * Если передан ключевой параметр $_REQUEST['param2'] = do, то добавляет страницу.
     * @throws coreException
     * @throws expectElementException
     * @throws wrongElementTypeAdminException
     */
    public function addPage()
    {
        // Валидация родительской страницы
//        $parent = $this->exceptElement('param0');
//        $type = (string)getRequest('param0');

        // Оформление данных
        $inputData = [
            'type' => 'page',
//            'parent' => $parent,
            'type-id' => getRequest('type-id'),
            'allowed-element-types' => [
                'page'
            ]
        ];

        // Если передан ключевой параметр
        if ($this->isSaveMode('param2')) {
            // Добавление страницы
            $this->saveAddedElementData($inputData);
            // Перенаправления в зависимости от режима кнопки "Добавить"
            $this->chooseRedirect();
        }

        // Установка типа данных (форма)
        $this->setDataType('form');

        // Установка типа операции (создание)
        $this->setActionType('create');
        // Установка результатов работы метода
        $data = $this->prepareData($inputData, 'page');
        $this->setData($data);
        $this->doData();
    }

    /**
     * Возвращает данные для построения формы редактирования страницы модуля.
     * Если передан ключевой параметр $_REQUEST['param1'] = do, то сохраняет изменения страницы.
     * @throws coreException
     * @throws expectElementException
     * @throws wrongElementTypeAdminException
     */
    public function editPage()
    {
        //Валидация редактируемой страницы
        $element = $this->expectElement('param0');

        // Оформление данных
        $inputData = [
            'element' => $element,
            'allowed-element-types' => [
                'page'
            ]
        ];

        // Если передан ключевой параметр
        if ($this->isSaveMode('param1')) {
            // Сохранение изменений страницы
            $this->saveEditedElementData($inputData);
            // Перенаправление в зависимости от режима кнопки "сохранить"
            $this->chooseRedirect();
        }

        // Установка типа данных (форма)
        $this->setDataType('form');
        // Установка типа операции (редактирование)
        $this->setActionType('modify');
        // Установка результатов работы метода
        $data = $this->prepareData($inputData, 'page');
        $this->setData($data);
        $this->doData();
    }

    /**
     * Удаляет страницы модуля
     * @throws coreException
     * @throws expectElementException
     * @throws wrongElementTypeAdminException
     */
    public function deletePages()
    {
        // Получение идентификатора страницы
        $elements = getRequest('element');

        if (!is_array($elements)) {
            $elements = [$elements];
        }

        // Обход массива идентификаторов
        foreach ($elements as $elementId) {
            // Валидация страницы
            $element = $this->expectElement($elementId, false, true);

            // Оформление данных страницы
            $params = [
                'element' => $element,
                "allowed-element-types" => [
                    'page'
                ]
            ];

            // Удаление страницы
            $this->deleteElement($params);
        }

        // Установка типа данных (список)
        $this->setDataType('list');
        // Установка типа операции (просмотр)
        $this->setActionType('view');
        // Установка результатов работы метода
        $data = $this->prepareData($elements, 'pages');
        $this->setData($data);
        $this->doData();
    }

    /**
     * Переключает активность страниц модуля
     * @throws coreException
     * @throws expectElementException
     * @throws requreMoreAdminPermissionsException
     * @throws wrongElementTypeAdminException
     */
    public function activity()
    {
        // TODO: можно использовать системный метод, заменив весь код одной строкой: $this->changeActivityForPages(['page']);
        // Получение идентификатора страницы
        $elements = getRequest('element');

        if (!is_array($elements)) {
            $elements = [$elements];
        }

        // Получение флага активности
        $is_active = getRequest('active');

        // Обход массива идентификаторов
        foreach ($elements as $elementId) {
            // Валидация страницы
            $element = $this->expectElement($elementId, false, true);

            // Оформление данных
            $params = [
                'element' => $element,
                'activity' => $is_active,
                'allowed-element-types' => [
                    'page'
                ]
            ];

            // Переключение активности
            $this->switchActivity($params);
            $element->commit();
        }

        // Установка типа данных (список)
        $this->setDataType('list');
        // Установка типа операции (просмотр)
        $this->setActionType('view');
        // Установка результатов работы метода
        $data = $this->prepareData($elements, 'pages');
        $this->setData($data);
        $this->doData();
    }

    /**
     * Возвращает объекты модуля
     * @return bool
     * @throws coreException
     * @throws selectorException
     */
    public function objects()
    {
        $this->setDataType('list');
        $this->setActionType('view');

        if ($this->module->ifNotXmlMode()) {
            $this->setDirectCallError();
            $this->doData();
            return true;
        }

        $limit = getRequest('per_page_limit');
        $curr_page = (int)getRequest('p');
        $offset = $limit * $curr_page;

        $objects = new selector('objects');
        $objects->types('object-type')->name('testModuleUnika', 'object');
        $objects->limit($offset, $limit);

        selectorHelper::detectFilters($objects);

        $result = $objects->result();
        $total = $objects->length();

        $this->setDataRange($limit, $offset);
        $data = $this->prepareData($result, 'objects');
        $this->setData($data, $total);
        $this->doData();
    }

    /**
     * Возвращает данные для построения формы добавления объекта модуля.
     * Если передан ключевой параметр $_REQUEST['param1'] = do, то добавляет объект.
     * @throws coreException
     * @throws publicAdminException
     * @throws wrongElementTypeAdminException
     */
    public function addObject()
    {
        $type = (string)getRequest('param0');
        $inputData = [
            'type' => $type,
            'type-id' => getRequest('type-id'),
            'allowed-element-types' => [
                'object'
            ]
        ];

        if ($this->isSaveMode('param1')) {
            $this->saveAddedObjectData($inputData);
            $this->chooseRedirect();
        }

        $this->setDataType('form');
        $this->setActionType('create');
        $data = $this->prepareData($inputData, 'object');
        $this->setData($data);
        $this->doData();
    }

    /**
     * Возвращает данные для построения формы редактирования объекта модуля.
     * Если передан ключевой параметр $_REQUEST['param1'] = do, то сохраняет изменения объекта.
     * @throws coreException
     * @throws expectObjectException
     */
    public function editObject()
    {
        $object = $this->expectObject('param0', true);
        $inputData = [
            'object' => $object,
            'allowed-element-types' => [
                'object'
            ]
        ];

        if ($this->isSaveMode('param1')) {
            $this->saveEditedObjectData($inputData);
            $this->chooseRedirect();
        }

        $this->setDataType('form');
        $this->setActionType('modify');
        $data = $this->prepareData($inputData, 'object');
        $this->setData($data);
        $this->doData();
    }

    /**
     * Удаляет объекты модуля
     * @throws coreException
     * @throws expectObjectException
     * @throws wrongElementTypeAdminException
     */
    public function deleteObjects()
    {
        $objects = getRequest('element');

        if (!is_array($objects)) {
            $objects = [$objects];
        }

        foreach ($objects as $objectId) {
            $object = $this->expectObject($objectId, false, true);

            $params = [
                'object' => $object,
                'allowed-element-types' => [
                    'object'
                ]
            ];

            $this->deleteObject($params);
        }

        $this->setDataType('list');
        $this->setActionType('view');
        $data = $this->prepareData($objects, 'objects');
        $this->setData($data);
        $this->doData();
    }

    /**
     * Возвращает настройки модуля.
     * Если передан ключевой параметр $_REQUEST['param0'] = do, то сохраняет настройки.
     * @throws coreException
     */
    public function config()
    {
        $groupKey = 'paging';
        $pagesKey = 'int:pages_per_page';
        $objectsKey = 'int:objects_per_page';

        $params = [
            $groupKey => [
                $pagesKey => null,
                $objectsKey => null
            ]
        ];

        $umiRegistry = Service::Registry();
        $pagesLimitXpath = $this->module->pagesLimitXpath;
        $objectsLimitXpath = $this->module->objectsLimitXpath;

        if ($this->isSaveMode()) {
            $params = $this->expectParams($params);
            $umiRegistry->set($pagesLimitXpath, $params[$groupKey][$pagesKey]);
            $umiRegistry->set($objectsLimitXpath, $params[$groupKey][$objectsKey]);
            $this->chooseRedirect();
        }

        $params[$groupKey][$pagesKey] = $umiRegistry->get($pagesLimitXpath);
        $params[$groupKey][$objectsKey] = $umiRegistry->get($objectsLimitXpath);

        $this->setConfigResult($params);
    }

    /**
     * Возвращает настройки табличного контрола
     * @param string $param контрольный параметр
     * @return array
     * @throws publicAdminException
     */
    public function getDatasetConfiguration($param = '')
    {
        switch ($param) {
            case 'pages' :
            {
                return $this->getPagesConfig();
            }
            case 'objects' :
            {
                return $this->getObjectsConfig();
            }
            default :
            {
                throw new publicAdminException(getLabel('error-config-not-found', 'testModuleUnika'));
            }
        }
    }

    /**
     * Возвращает настройки табличного контрола для страниц модуля
     * @return array
     */
    protected function getPagesConfig()
    {
        return [
            'methods' => [
                [
                    'title' => getLabel('smc-load'),
                    'forload' => true,
                    'module' => 'testModuleUnika',
                    '#__name' => 'pages'
                ],
                [
                    'title' => getLabel('smc-delete'),
                    'module' => 'testModuleUnika',
                    '#__name' => 'deletePages',
                    'aliases' => 'tree_delete_element,delete,del'
                ],
                [
                    'title' => getLabel('smc-activity'),
                    'module' => 'testModuleUnika',
                    '#__name' => 'activity',
                    'aliases' => 'tree_set_activity,activity'
                ],
            ],
            'types' => [
                [
                    'common' => 'true',
                    'id' => 'page'
                ]
            ],
            'stoplist' => [
                'locktime',
                'lockuser',
                'rate_voters',
                'rate_sum'
            ],
            'default' => 'name[250px]|content[750px]'
        ];
    }

    /**
     * Возвращает настройки табличного контрола для объектов модуля
     * @return array
     */
    protected function getObjectsConfig()
    {
        return [
            'methods' => [
                [
                    'title' => getLabel('smc-load'),
                    'forload' => true,
                    'module' => 'testModuleUnika',
                    '#__name' => 'objects'
                ],
                [
                    'title' => getLabel('smc-delete'),
                    'module' => 'testModuleUnika',
                    '#__name' => 'deleteObjects',
                    'aliases' => 'tree_delete_element,delete,del'
                ]
            ],
            'types' => [
                [
                    'common' => 'true',
                    'id' => 'object'
                ]
            ],
            'stoplist' => [],
            'default' => 'name[250px]'
        ];
    }
}