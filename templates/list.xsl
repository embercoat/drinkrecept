<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template name="list">
<div id="captions">
<xsl:for-each select="/root/list/group">
    <xsl:if test="@cap">
        <a class="captions"><xsl:attribute name="href">#<xsl:value-of select="@cap" /></xsl:attribute><xsl:value-of select="@cap" /></a>
    </xsl:if>
</xsl:for-each>
</div>
<xsl:for-each select="/root/list/group">
    <div class="list_boxes">
        <xsl:if test="@cap">
            <h1 class="cap"><a><xsl:attribute name="name"><xsl:value-of select="@cap" /></xsl:attribute><xsl:attribute name="id"><xsl:value-of select="@cap" /></xsl:attribute><xsl:value-of select="@cap" /></a></h1>
        </xsl:if>
        <xsl:for-each select="item">
            <a><xsl:attribute name="href"><xsl:value-of select="@href" /></xsl:attribute><xsl:value-of select="name" /></a>
            <xsl:if test="@add">
                <a><xsl:attribute name="href"><xsl:value-of select="@add" /></xsl:attribute>[Add]</a>
            </xsl:if>
            <br />
        </xsl:for-each>
    </div>
</xsl:for-each>

</xsl:template>
</xsl:stylesheet>
