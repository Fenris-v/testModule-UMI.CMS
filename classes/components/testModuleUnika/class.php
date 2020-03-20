<?php

use UmiCms\Service;

/**
 * Тестовый модуль
 * Отвечает за:
 *
 * 1) Ничто
 */
class testModuleUnika extends def_module
{
    /**
     * @var string $pagesLimitXpath путь до опции реестра, отвечающего за ограничение количества выводимых страниц
     */
    public $pagesLimitXpath = '//modules/testModuleUnika/paging/pages';

    /**
     * @var string $objectsLimitXpath путь до опции реестра, отвечающего за ограничение количества выводимых объектов
     */
    public $objectsLimitXpath = '//modules/testModuleUnika/paging/objects';

    /** Конструктор */
    public function __construct()
    {
        parent::__construct();

        // Проверка на админа
        if (Service::Request()->isAdmin()) {
            // Создание вкладок для административной панели
            $this->initTabs();
            // Подключение классов функционала административной панели
            $this->includeAdminClasses();
        } else {
            // Подключение классов клиентского функционала
            $this->includeGuestClasses();
        }

        $this->includeCommonClasses();

        return $this;
    }

    /**
     * Создает вкладки административной панели модуля
     */
    protected function initTabs()
    {
        $configTabs = $this->getConfigTabs();

        if ($configTabs instanceof iAdminModuleTabs) {
            $configTabs->add("config");
        }

        $commonTabs = $this->getCommonTabs();

        if ($commonTabs instanceof iAdminModuleTabs) {
            $commonTabs->add('pages');
            $commonTabs->add('objects');
        }

        return $this;
    }

    /**
     * Подключает классы функционала административной панели
     */
    protected function includeAdminClasses()
    {
        $this->__loadLib("admin.php");
        $this->__implement("TestModuleUnikaAdmin");

        $this->loadAdminExtension();

        $this->__loadLib("customMacros.php");
        $this->__implement("TestModuleUnikaCustomAdmin", true);

        return $this;
    }

    /**
     * Подключает классы функционала клиентской части
     */
    protected function includeGuestClasses()
    {
        $this->__loadLib("macros.php");
        $this->__implement("TestModuleUnikaMacros");

        $this->loadSiteExtension();

        $this->__loadLib("customMacros.php");
        $this->__implement("TestModuleUnikaCustomMacros", true);

        return $this;
    }

    /**
     * Подключает общие классы функционала
     */
    protected function includeCommonClasses()
    {
        $this->loadCommonExtension();
        $this->loadTemplateCustoms();

        return $this;
    }

    /**
     * Возвращает ссылки на форму редактирования страницы модуля и
     * на форму добавления дочернего элемента к странице.
     * @param int $element_id идентификатор страницы модуля
     * @param string|bool $element_type тип страницы модуля
     * @return array
     */
    public function getEditLink($element_id, $element_type = false)
    {
        return [
            false,
            $this->pre_lang . "/admin/testModuleUnika/editPage/{$element_id}/"
        ];
    }

    /**
     * Возвращает ссылку на редактирование объектов в административной панели
     * @param int $objectId ID редактируемого объекта
     * @param string|bool $type метод типа объекта
     * @return string
     */
    public function getObjectEditLink($objectId, $type = false)
    {
        return $this->pre_lang . "/admin/testModuleUnika/editObject/{$objectId}/";
    }


}