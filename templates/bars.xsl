<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template name="bars">
<a href="/bars/save/">Spara din bar</a><br /><br />
<ul>
    <xsl:for-each select="/root/bars/bar">
        <li>
            <xsl:value-of select="name" />
            <a>
                <xsl:attribute name="href">/backend/loadbar/<xsl:value-of select="@bid" /></xsl:attribute>[Ladda]
            </a>
            <a>
                <xsl:attribute name="href">javascript:updatePopup(<xsl:value-of select="@bid" />);</xsl:attribute>[Uppdatera]
            </a>
            <a>
                <xsl:attribute name="href">javascript:delPopup(<xsl:value-of select="@bid" />);</xsl:attribute>[Ta Bort]
            </a>
        </li>
    </xsl:for-each>
</ul>
</xsl:template>
</xsl:stylesheet>
