<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template name="mydrinks">
<ul>
<xsl:for-each select="/root/mydrinks/drink">
    <li><a><xsl:attribute name="href">/drinks/<xsl:value-of select="did" /></xsl:attribute><xsl:value-of select="name" /></a></li>
</xsl:for-each>
</ul>
</xsl:template>
</xsl:stylesheet>
