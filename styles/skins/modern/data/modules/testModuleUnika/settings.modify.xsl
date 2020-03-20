<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://common/autoupdate">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <!-- Шаблон вкладки "Состояние обновлений" -->
        <xsl:template match="data[@type = 'settings' and @action = 'modify']">
            <div class="tabs-content module">
                <div class="section selected">
                    <div class="location">
                        <xsl:call-template name="entities.help.button" />
                    </div>

                    <div class="layout">
                        <div class="column">
                            <form method="post" action="do/" enctype="multipart/form-data">
                                <xsl:apply-templates select="group" mode="settings.modify"/>
                            </form>
                        </div>
                        <div class="column">
                            <xsl:call-template name="entities.help.content" />
                        </div>
                    </div>
                </div>
            </div>
            <div id="integrity-error-message" class="hidden">
                <p>&label-integrity-violation-found;</p>
                <div class="to-right">
                    <a class="btn color-blue btn-small close-dialog">&label-integrity-close-dialog;</a>
                    <a class="btn color-blue btn-small retry-button">&label-integrity-ignore-risk;</a>
                </div>
            </div>

            <div class="row">
                <xsl:call-template name="std-form-buttons-settings"/>
            </div>

        </xsl:template>

</xsl:stylesheet>
