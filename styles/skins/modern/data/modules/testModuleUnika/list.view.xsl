<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://common/testModuleUnika" [
        <!ENTITY sys-module        'testModuleUnika'>
        <!ENTITY sys-method-addPage        'addPage'>
        <!ENTITY sys-method-addObject        'addObject'>
        <!ENTITY sys-method-addObject-type        'object'>
        <!ENTITY sys-method-addPage-type        'page'>
		]>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:variable name="add-method" select="'addPage'"/>
    <xsl:variable name="basic-type" select="'page'"/>

    <xsl:template match="/result[@method = 'pages']/data[@type = 'list' and @action = 'view']">
        <div class="location">
            <div class="imgButtonWrapper loc-left " xmlns:umi="http://www.umi-cms.ru/TR/umi">
                <a id="" href="{$lang-prefix}/admin/&sys-module;/&sys-method-addPage;/&sys-method-addPage-type;/do/"
                   class="btn color-blue" umi:type="appointment::page">
                    <xsl:text>&do-nothing-button;</xsl:text>
                </a>
            </div>
            <xsl:call-template name="entities.help.button"/>
        </div>
        <div class="layout">
            <div class="column">
                <xsl:call-template name="ui-smc-table">
                    <xsl:with-param name="control-params" select="$method" />
                    <xsl:with-param name="flat-mode">1</xsl:with-param>
                    <xsl:with-param name="enable-objects-activity">1</xsl:with-param>
                </xsl:call-template>
            </div>
            <div class="column">
                <xsl:call-template name="entities.help.content"/>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="/result[@method = 'objects']/data[@type = 'list' and @action = 'view']">
        <div class="location">
            <div class="imgButtonWrapper loc-left " xmlns:umi="http://www.umi-cms.ru/TR/umi">
                <a id="" href="{$lang-prefix}/admin/&sys-module;/&sys-method-addObject;/&sys-method-addObject-type;/"
                   class="btn color-blue" umi:type="appointment::object">
                    <xsl:text>&do-nothing-objects-button;</xsl:text>
                </a>
            </div>
            <xsl:call-template name="entities.help.button">
                <xsl:with-param name="control-params" select="$method" />
                <xsl:with-param name="flat-mode">1</xsl:with-param>
                <xsl:with-param name="enable-objects-activity">1</xsl:with-param>
            </xsl:call-template>
        </div>
        <div class="layout">
            <div class="column">
                <xsl:call-template name="ui-smc-table">
                    <xsl:with-param name="control-params" select="$method" />
                    <xsl:with-param name="flat-mode">1</xsl:with-param>
                    <xsl:with-param name="enable-objects-activity">1</xsl:with-param>
                </xsl:call-template>
            </div>
            <div class="column">
                <xsl:call-template name="entities.help.content"/>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="/result[@method = 'testing']/data" priority="1">
        <div class="location">
            <xsl:call-template name="entities.help.button"/>
        </div>
        <div class="layout">
            <div class="column">
                <xsl:call-template name="ui-smc-table">
                    <xsl:with-param name="flat-mode">1</xsl:with-param>
                    <xsl:with-param name="enable-objects-activity">1</xsl:with-param>
                </xsl:call-template>
            </div>
            <div class="column">
                <xsl:call-template name="entities.help.content"/>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>
